<?php

namespace Tests\Feature;

use App\Enums\WorkProgress;
use App\Mail\ResetPasswordMail;
use App\Models\CustomOrder;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\Feature\Blackbox\BlackboxTestCase;

class RevisionsTest extends BlackboxTestCase
{
    use RefreshDatabase;

    /**
     * Test 1: Reset password via SMTP generates 8-character random password and sends email.
     */
    public function test_reset_password_generates_8_char_password_and_sends_email(): void
    {
        Mail::fake();

        $user = $this->customer([
            'email' => 'user@example.com',
            'password' => Hash::make('oldpassword'),
        ]);

        $response = $this->post(route('password.email'), [
            'email' => 'user@example.com',
        ]);

        $response->assertSessionHas('status');
        $user->refresh();

        // User can now login with the new password
        Mail::assertSent(ResetPasswordMail::class, function ($mail) use ($user) {
            $newPassword = $mail->newPassword;
            $this->assertEquals(8, strlen($newPassword));
            $this->assertTrue(Hash::check($newPassword, $user->password));
            return $mail->hasTo('user@example.com');
        });
    }

    public function test_reset_password_fails_for_unknown_email(): void
    {
        Mail::fake();

        $response = $this->post(route('password.email'), [
            'email' => 'unknown@example.com',
        ]);

        $response->assertSessionHasErrors(['email']);
        Mail::assertNothingSent();
    }

    /**
     * Test 2: Employee role access and percentage-based WorkProgress enum updates.
     */
    public function test_employee_can_view_approved_orders_and_update_progress_enum(): void
    {
        $employee = User::factory()->create([
            'role' => 'employee',
            'email' => 'karyawan@example.com',
            'password' => Hash::make('password'),
        ]);

        $approvedOrder = $this->order(null, [
            'status' => 'confirmed',
            'progress_percentage' => 0,
            'total_price' => 1000000,
            'dp_amount' => 500000,
        ]);

        Payment::create([
            'custom_order_id' => $approvedOrder->id,
            'amount' => 500000,
            'payment_type' => 'down_payment',
            'status' => 'verified',
            'bank_name' => 'BCA',
            'account_name' => 'Customer',
            'proof_image_path' => 'proof.jpg',
        ]);

        $pendingOrder = $this->order(null, [
            'status' => 'pending',
        ]);

        // Employee login redirect
        $loginResponse = $this->post(route('login'), [
            'email' => 'karyawan@example.com',
            'password' => 'password',
        ]);
        $loginResponse->assertRedirect(route('employee.dashboard'));

        // Employee can access employee portal
        $indexResponse = $this->actingAs($employee)->get(route('employee.orders.index'));
        $indexResponse->assertOk();
        $indexResponse->assertSeeText($approvedOrder->order_code);
        $indexResponse->assertDontSeeText($pendingOrder->order_code);

        // Employee can update work progress to 50% (Perakitan & Pengelasan)
        $updateResponse = $this->actingAs($employee)
            ->put(route('employee.orders.updateProgress', $approvedOrder->id), [
                'progress_percentage' => WorkProgress::ASSEMBLY->value, // 50
                'progress_notes' => 'Rangka telah dipotong dan dirakit.',
            ]);

        $updateResponse->assertRedirect(route('employee.orders.show', $approvedOrder->id));
        $updateResponse->assertSessionHas('success');

        $approvedOrder->refresh();
        $this->assertEquals(50, $approvedOrder->progress_percentage);
        $this->assertEquals('in_production', $approvedOrder->status);
        $this->assertEquals('Rangka telah dipotong dan dirakit.', $approvedOrder->progress_notes);
        $this->assertEquals(WorkProgress::ASSEMBLY, $approvedOrder->workProgress());
    }

    public function test_employee_cannot_update_progress_when_payment_not_made(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);

        // Order is confirmed, but NO payment has been made yet
        $unpaidOrder = $this->order(null, [
            'status' => 'confirmed',
            'progress_percentage' => 0,
            'total_price' => 1000000,
            'dp_amount' => 500000,
        ]);

        $response = $this->actingAs($employee)
            ->put(route('employee.orders.updateProgress', $unpaidOrder->id), [
                'progress_percentage' => WorkProgress::PREPARATION->value, // 25
                'progress_notes' => 'Mencoba update tanpa DP.',
            ]);

        $response->assertSessionHas('error');
        $unpaidOrder->refresh();
        $this->assertEquals(0, $unpaidOrder->progress_percentage);
        $this->assertEquals('confirmed', $unpaidOrder->status);
    }

    public function test_customer_cannot_access_employee_portal(): void
    {
        $customer = $this->customer();

        $response = $this->actingAs($customer)->get(route('employee.orders.index'));
        $response->assertForbidden();
    }

    public function test_work_progress_update_rejects_invalid_percentage(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);
        $order = $this->order(null, [
            'status' => 'in_production',
            'total_price' => 1000000,
            'dp_amount' => 500000,
        ]);

        Payment::create([
            'custom_order_id' => $order->id,
            'amount' => 500000,
            'payment_type' => 'down_payment',
            'status' => 'verified',
            'bank_name' => 'BCA',
            'account_name' => 'Customer',
            'proof_image_path' => 'proof.jpg',
        ]);

        $response = $this->actingAs($employee)
            ->put(route('employee.orders.updateProgress', $order->id), [
                'progress_percentage' => 33, // Not in enum [0, 25, 50, 75, 100]
            ]);

        $response->assertSessionHasErrors(['progress_percentage']);
    }

    /**
     * Test 3: Two-stage payment flow (Down Payment -> Pelunasan).
     */
    public function test_payment_flow_enforces_dp_before_full_payment(): void
    {
        $this->fakePublicDisk();
        $customer = $this->customer();
        $admin = $this->admin();

        $order = $this->order($customer, [
            'status' => 'confirmed',
            'total_price' => 2000000,
            'dp_amount' => 1000000, // 50%
        ]);

        // Attempting full payment before DP is verified must be rejected
        $rejectedFullPay = $this->actingAs($customer)
            ->post(route('customer.payments.store', $order->id), [
                'payment_type' => 'full_payment',
                'amount' => 1000000,
                'proof_image' => UploadedFile::fake()->create('full.jpg', 64, 'image/jpeg'),
                'bank_name' => 'BCA',
                'account_name' => 'Customer',
            ]);

        $rejectedFullPay->assertSessionHas('error');
        $this->assertDatabaseMissing('payments', ['payment_type' => 'full_payment']);

        // Uploading DP payment
        $uploadDp = $this->actingAs($customer)
            ->post(route('customer.payments.store', $order->id), [
                'payment_type' => 'down_payment',
                'amount' => 1000000,
                'proof_image' => UploadedFile::fake()->create('dp.jpg', 64, 'image/jpeg'),
                'bank_name' => 'BCA',
                'account_name' => 'Customer',
            ]);

        $uploadDp->assertRedirect(route('customer.orders.show', $order->id));
        $dpPayment = $order->payments()->where('payment_type', 'down_payment')->first();
        $this->assertNotNull($dpPayment);

        // Admin verifies DP payment -> Order transitions to in_production
        $verifyDp = $this->actingAs($admin)
            ->put(route('admin.payments.verify', $dpPayment->id));

        $verifyDp->assertRedirect(route('admin.payments.index'));

        $order->refresh();
        $this->assertTrue($order->isDpPaid());
        $this->assertEquals('in_production', $order->status);
        $this->assertEquals(1000000, $order->remainingBalance());

        // Now customer can upload full payment (Pelunasan)
        $uploadFull = $this->actingAs($customer)
            ->post(route('customer.payments.store', $order->id), [
                'payment_type' => 'full_payment',
                'amount' => 1000000,
                'proof_image' => UploadedFile::fake()->create('full.jpg', 64, 'image/jpeg'),
                'bank_name' => 'BCA',
                'account_name' => 'Customer',
            ]);

        $uploadFull->assertRedirect(route('customer.orders.show', $order->id));
        $fullPayment = $order->payments()->where('payment_type', 'full_payment')->first();
        $this->assertNotNull($fullPayment);

        // Admin verifies full payment -> Order is fully paid
        $this->actingAs($admin)->put(route('admin.payments.verify', $fullPayment->id));

        $order->refresh();
        $this->assertTrue($order->isFullyPaid());
        $this->assertEquals(0, $order->remainingBalance());
    }

    /**
     * Test 4: Change Password feature for authenticated users.
     */
    public function test_user_can_change_password_with_correct_current_password(): void
    {
        $user = $this->customer([
            'password' => Hash::make('old-secret-password'),
        ]);

        $response = $this->actingAs($user)
            ->put(route('password.update'), [
                'current_password' => 'old-secret-password',
                'password' => 'new-secure-password123',
                'password_confirmation' => 'new-secure-password123',
            ]);

        $response->assertSessionHas('status');
        $user->refresh();
        $this->assertTrue(Hash::check('new-secure-password123', $user->password));
    }

    public function test_user_cannot_change_password_with_incorrect_current_password(): void
    {
        $user = $this->customer([
            'password' => Hash::make('correct-password'),
        ]);

        $response = $this->actingAs($user)
            ->put(route('password.update'), [
                'current_password' => 'wrong-password',
                'password' => 'new-secure-password123',
                'password_confirmation' => 'new-secure-password123',
            ]);

        $response->assertSessionHasErrors(['current_password']);
        $user->refresh();
        $this->assertTrue(Hash::check('correct-password', $user->password));
    }

    public function test_user_cannot_change_password_with_short_or_mismatched_confirmation(): void
    {
        $user = $this->customer([
            'password' => Hash::make('correct-password'),
        ]);

        $shortResponse = $this->actingAs($user)
            ->put(route('password.update'), [
                'current_password' => 'correct-password',
                'password' => 'short',
                'password_confirmation' => 'short',
            ]);

        $shortResponse->assertSessionHasErrors(['password']);

        $mismatchResponse = $this->actingAs($user)
            ->put(route('password.update'), [
                'current_password' => 'correct-password',
                'password' => 'new-password-1',
                'password_confirmation' => 'new-password-different',
            ]);

        $mismatchResponse->assertSessionHasErrors(['password']);
    }

    public function test_guest_cannot_access_change_password(): void
    {
        $response = $this->get(route('password.change'));
        $response->assertRedirect(route('login'));
    }

    public function test_employee_change_password_renders_within_employee_panel(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);

        $response = $this->actingAs($employee)->get(route('password.change'));
        $response->assertOk();
        $response->assertSeeText('Panel Karyawan');
        $response->assertSeeText('Kembali ke Dashboard Karyawan');
    }

    public function test_admin_change_password_renders_within_admin_panel(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->get(route('password.change'));
        $response->assertOk();
        $response->assertSeeText('Dashboard Admin');
        $response->assertSeeText('Kembali ke Dashboard Admin');
    }

    /**
     * Test 5: Dedicated Employee Panel Dashboard and Metrics.
     */
    public function test_employee_can_access_dashboard_with_kpis(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);

        // Create orders with different statuses
        $this->order(null, ['status' => 'confirmed', 'product_name' => 'Pagar Minimalis']);
        $this->order(null, ['status' => 'in_production', 'progress_percentage' => 50, 'product_name' => 'Kanopi Besi']);
        $this->order(null, ['status' => 'completed', 'progress_percentage' => 100, 'product_name' => 'Teralis Jendela']);

        $response = $this->actingAs($employee)->get(route('employee.dashboard'));
        $response->assertOk();
        $response->assertSeeText('Selamat Bertugas');
        $response->assertSeeText('Siap Dikerjakan');
        $response->assertSeeText('Sedang Dikerjakan');
        $response->assertSeeText('Kanopi Besi');
        $response->assertSeeText('Teralis Jendela');
    }

    public function test_customer_cannot_access_employee_dashboard(): void
    {
        $customer = $this->customer();

        $response = $this->actingAs($customer)->get(route('employee.dashboard'));
        $response->assertForbidden();
    }

    /**
     * Test 6: Admin cannot mark order as completed if workshop process is not 100% or payment not full.
     */
    public function test_admin_cannot_complete_order_if_work_progress_not_100(): void
    {
        $admin = $this->admin();
        $order = $this->order(null, [
            'status' => 'in_production',
            'progress_percentage' => 50, // work is NOT complete
            'total_price' => 1000000,
            'dp_amount' => 500000,
        ]);

        Payment::create([
            'custom_order_id' => $order->id,
            'amount' => 1000000,
            'payment_type' => 'full_payment',
            'status' => 'verified',
            'bank_name' => 'BCA',
            'account_name' => 'Customer',
            'proof_image_path' => 'proof.jpg',
        ]);

        $response = $this->actingAs($admin)
            ->put(route('admin.orders.updateStatus', $order->id), [
                'status' => 'completed',
            ]);

        $response->assertSessionHas('error');
        $order->refresh();
        $this->assertEquals('in_production', $order->status);
    }

    public function test_admin_cannot_complete_order_if_payment_not_fully_paid(): void
    {
        $admin = $this->admin();
        $order = $this->order(null, [
            'status' => 'in_production',
            'progress_percentage' => 100, // work is finished
            'total_price' => 1000000,
            'dp_amount' => 500000,
        ]);

        // Only DP paid, remaining 500,000 unpaid
        Payment::create([
            'custom_order_id' => $order->id,
            'amount' => 500000,
            'payment_type' => 'down_payment',
            'status' => 'verified',
            'bank_name' => 'BCA',
            'account_name' => 'Customer',
            'proof_image_path' => 'proof.jpg',
        ]);

        $response = $this->actingAs($admin)
            ->put(route('admin.orders.updateStatus', $order->id), [
                'status' => 'completed',
            ]);

        $response->assertSessionHas('error');
        $order->refresh();
        $this->assertEquals('in_production', $order->status);
    }

    public function test_admin_can_complete_order_when_progress_100_and_fully_paid(): void
    {
        $admin = $this->admin();
        $order = $this->order(null, [
            'status' => 'in_production',
            'progress_percentage' => 100,
            'total_price' => 1000000,
            'dp_amount' => 500000,
        ]);

        Payment::create([
            'custom_order_id' => $order->id,
            'amount' => 500000,
            'payment_type' => 'down_payment',
            'status' => 'verified',
            'bank_name' => 'BCA',
            'account_name' => 'Customer',
            'proof_image_path' => 'dp.jpg',
        ]);

        Payment::create([
            'custom_order_id' => $order->id,
            'amount' => 500000,
            'payment_type' => 'full_payment',
            'status' => 'verified',
            'bank_name' => 'BCA',
            'account_name' => 'Customer',
            'proof_image_path' => 'full.jpg',
        ]);

        $response = $this->actingAs($admin)
            ->put(route('admin.orders.updateStatus', $order->id), [
                'status' => 'completed',
            ]);

        $response->assertSessionHas('success');
        $order->refresh();
        $this->assertEquals('completed', $order->status);
    }

    public function test_employee_can_upload_progress_photo_during_progress_update(): void
    {
        Storage::fake('public');

        $employee = User::factory()->create([
            'role' => 'employee',
        ]);

        $order = $this->order(null, [
            'status' => 'confirmed',
            'progress_percentage' => 0,
            'total_price' => 1000000,
            'dp_amount' => 500000,
        ]);

        Payment::create([
            'custom_order_id' => $order->id,
            'amount' => 500000,
            'payment_type' => 'down_payment',
            'status' => 'verified',
            'bank_name' => 'BCA',
            'account_name' => 'Customer',
            'proof_image_path' => 'dp.jpg',
        ]);

        $fakePhoto = UploadedFile::fake()->image('welding_cutting.jpg', 600, 400);

        $response = $this->actingAs($employee)
            ->put(route('employee.orders.updateProgress', $order->id), [
                'progress_percentage' => 50,
                'progress_notes' => 'Proses pengelasan dan perakitan kerangka utama selesai.',
                'progress_photo' => $fakePhoto,
            ]);

        $response->assertRedirect(route('employee.orders.show', $order->id));
        $response->assertSessionHas('success');

        $order->refresh();
        $this->assertEquals(50, $order->progress_percentage);
        $this->assertEquals('in_production', $order->status);
        $this->assertNotNull($order->progress_photo_path);
        Storage::disk('public')->assertExists($order->progress_photo_path);
    }

    public function test_customer_can_see_settlement_guidance_and_technician_progress_photo(): void
    {
        $customer = $this->customer();

        $order = $this->order($customer, [
            'status' => 'in_production',
            'progress_percentage' => 75,
            'total_price' => 1000000,
            'dp_amount' => 500000,
            'progress_photo_path' => 'progress-photos/welding.jpg',
            'progress_notes' => 'Pengecatan dasar selesai.',
        ]);

        Payment::create([
            'custom_order_id' => $order->id,
            'amount' => 500000,
            'payment_type' => 'down_payment',
            'status' => 'verified',
            'bank_name' => 'BCA',
            'account_name' => 'Customer',
            'proof_image_path' => 'dp.jpg',
        ]);

        $response = $this->actingAs($customer)
            ->get(route('customer.orders.show', $order->id));

        $response->assertOk();
        // Payment guidance must be present
        $response->assertSee('Panduan Pelunasan Tagihan');
        $response->assertSee('admin hanya dapat mengubah status pesanan menjadi');
        $response->assertSee('Bayar Pelunasan');
        // Progress photo and modal must be present
        $response->assertSee('progress-photos/welding.jpg');
        $response->assertSee('Dokumentasi Fisik Pengerjaan');
        $response->assertSee('Pengecatan dasar selesai.');
    }

    public function test_public_tracking_displays_settlement_note_and_progress_photo(): void
    {
        $order = $this->order(null, [
            'order_code' => 'ORD-TEST-TRACK-999',
            'status' => 'in_production',
            'progress_percentage' => 75,
            'total_price' => 2000000,
            'dp_amount' => 1000000,
            'progress_photo_path' => 'progress-photos/track_item.jpg',
            'progress_notes' => 'Perakitan plat dan finishing las.',
        ]);

        Payment::create([
            'custom_order_id' => $order->id,
            'amount' => 1000000,
            'payment_type' => 'down_payment',
            'status' => 'verified',
            'bank_name' => 'BCA',
            'account_name' => 'Customer',
            'proof_image_path' => 'dp.jpg',
        ]);

        $response = $this->get(route('orders.track', ['order_code' => 'ORD-TEST-TRACK-999']));

        $response->assertOk();
        $response->assertSee('Pelunasan sisa tagihan wajib diselesaikan sebelum admin mengubah status ke');
        $response->assertSee('progress-photos/track_item.jpg');
        $response->assertSee('Perakitan plat dan finishing las.');
    }
}
