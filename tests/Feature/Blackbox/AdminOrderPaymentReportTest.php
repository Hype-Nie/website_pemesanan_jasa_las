<?php

namespace Tests\Feature\Blackbox;

use App\Models\CustomOrder;
use App\Models\Payment;

class AdminOrderPaymentReportTest extends BlackboxTestCase
{
    public function test_admin_can_filter_and_search_orders(): void
    {
        $admin = $this->admin();
        $customer = $this->customer(['name' => 'Budi Santoso']);
        $pending = $this->order($customer, [
            'order_code' => 'ORD-PENDING',
            'product_name' => 'Pagar Searchable',
            'status' => 'pending',
        ]);
        $completed = $this->order($customer, [
            'order_code' => 'ORD-COMPLETE',
            'product_name' => 'Kanopi Selesai',
            'status' => 'completed',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.orders.index', [
            'status' => 'pending',
            'search' => 'Searchable',
        ]));

        $response->assertOk();
        $response->assertSeeText($pending->order_code);
        $response->assertDontSeeText($completed->order_code);
    }

    public function test_admin_can_update_order_status_price_and_notes(): void
    {
        $admin = $this->admin();
        $order = $this->order(null, ['status' => 'pending']);

        $response = $this->actingAs($admin)
            ->put(route('admin.orders.updateStatus', $order->id), [
                'status' => 'confirmed',
                'total_price' => 1750000,
                'admin_notes' => '<b>Survey selesai</b>',
            ]);

        $response->assertRedirect(route('admin.orders.show', $order->id));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('custom_orders', [
            'id' => $order->id,
            'status' => 'confirmed',
            'total_price' => 1750000,
            'admin_notes' => 'Survey selesai',
        ]);
    }

    public function test_admin_cannot_start_production_without_verified_payment(): void
    {
        $admin = $this->admin();
        $order = $this->order(null, ['status' => 'confirmed']);

        $blocked = $this->actingAs($admin)
            ->from(route('admin.orders.show', $order->id))
            ->put(route('admin.orders.updateStatus', $order->id), [
                'status' => 'in_production',
            ]);

        $blocked->assertRedirect(route('admin.orders.show', $order->id));
        $blocked->assertSessionHas('error');
        $this->assertDatabaseHas('custom_orders', [
            'id' => $order->id,
            'status' => 'confirmed',
        ]);

        $this->payment($order, [
            'status' => 'verified',
            'verified_at' => now(),
        ]);

        $allowed = $this->actingAs($admin)
            ->put(route('admin.orders.updateStatus', $order->id), [
                'status' => 'in_production',
            ]);

        $allowed->assertRedirect(route('admin.orders.show', $order->id));
        $this->assertDatabaseHas('custom_orders', [
            'id' => $order->id,
            'status' => 'in_production',
        ]);
    }

    public function test_admin_order_status_validation_rejects_unknown_status_and_negative_price(): void
    {
        $admin = $this->admin();
        $order = $this->order();

        $this->actingAs($admin)
            ->from(route('admin.orders.show', $order->id))
            ->put(route('admin.orders.updateStatus', $order->id), [
                'status' => 'archived',
                'total_price' => -10,
            ])
            ->assertRedirect(route('admin.orders.show', $order->id))
            ->assertSessionHasErrors(['status', 'total_price']);
    }

    public function test_admin_payment_index_only_shows_pending_payments(): void
    {
        $admin = $this->admin();
        $pending = $this->payment(null, ['account_name' => 'Pending Payer']);
        $verified = Payment::factory()->verified()->create(['account_name' => 'Verified Payer']);

        $response = $this->actingAs($admin)->get(route('admin.payments.index'));

        $response->assertOk();
        $response->assertSeeText($pending->customOrder->order_code);
        $response->assertSeeText('Pending Payer');
        $response->assertDontSeeText('Verified Payer');
    }

    public function test_admin_can_verify_and_reject_payments(): void
    {
        $admin = $this->admin();
        $toVerify = $this->payment();
        $toReject = $this->payment();

        $this->actingAs($admin)
            ->put(route('admin.payments.verify', $toVerify->id))
            ->assertRedirect(route('admin.payments.index'));

        $this->assertDatabaseHas('payments', [
            'id' => $toVerify->id,
            'status' => 'verified',
        ]);
        $this->assertNotNull($toVerify->fresh()->verified_at);

        $this->actingAs($admin)
            ->from(route('admin.payments.index'))
            ->put(route('admin.payments.reject', $toReject->id), [
                'admin_notes' => '<b>Nominal tidak sesuai</b>',
            ])
            ->assertRedirect(route('admin.payments.index'));

        $this->assertDatabaseHas('payments', [
            'id' => $toReject->id,
            'status' => 'rejected',
            'admin_notes' => 'Nominal tidak sesuai',
        ]);
    }

    public function test_admin_payment_reject_requires_notes(): void
    {
        $admin = $this->admin();
        $payment = $this->payment();

        $this->actingAs($admin)
            ->from(route('admin.payments.index'))
            ->put(route('admin.payments.reject', $payment->id), [
                'admin_notes' => '',
            ])
            ->assertRedirect(route('admin.payments.index'))
            ->assertSessionHasErrors('admin_notes');
    }

    public function test_admin_report_index_and_exports_show_period_transactions(): void
    {
        $admin = $this->admin();
        $order = $this->order(null, [
            'product_name' => 'Produk Report',
            'status' => 'completed',
            'total_price' => 2000000,
            'created_at' => now(),
        ]);
        $this->payment($order, [
            'amount' => 2000000,
            'status' => 'verified',
            'verified_at' => now(),
        ]);

        $query = [
            'from' => now()->format('Y-m-d'),
            'to' => now()->format('Y-m-d'),
        ];

        $index = $this->actingAs($admin)->get(route('admin.reports.index', $query));

        $index->assertOk();
        $index->assertSeeText('Produk Report');
        $index->assertSeeText('Rp 2.000.000');

        $csv = $this->actingAs($admin)->get(route('admin.reports.export.csv', $query));

        $csv->assertOk();
        $this->assertStringStartsWith('text/csv', $csv->headers->get('content-type'));
        $csv->assertDownload('laporan_' . now()->format('Y-m-d') . '_sd_' . now()->format('Y-m-d') . '.csv');
        $this->assertStringContainsString('Produk Report', $csv->streamedContent());

        $pdf = $this->actingAs($admin)->get(route('admin.reports.export.pdf', $query));

        $pdf->assertOk();
        $pdf->assertDownload('laporan_' . now()->format('Y-m-d') . '_sd_' . now()->format('Y-m-d') . '.pdf');
    }
}
