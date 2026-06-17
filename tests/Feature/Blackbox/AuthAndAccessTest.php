<?php

namespace Tests\Feature\Blackbox;

use Illuminate\Support\Facades\Hash;

class AuthAndAccessTest extends BlackboxTestCase
{
    public function test_customer_registration_creates_customer_and_logs_in(): void
    {
        $response = $this->post(route('register'), [
            'name' => '<b>Andi Customer</b>',
            'email' => 'andi@example.com',
            'phone' => '081234567891',
            'address' => '<script>bad()</script>Majalengka',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('customer.orders.index'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'name' => 'Andi Customer',
            'email' => 'andi@example.com',
            'role' => 'customer',
        ]);
    }

    public function test_registration_validation_rejects_duplicate_email_and_short_password(): void
    {
        $this->customer(['email' => 'duplicate@example.com']);

        $response = $this->from(route('register'))->post(route('register'), [
            'name' => '',
            'email' => 'duplicate@example.com',
            'password' => 'short',
            'password_confirmation' => 'different',
        ]);

        $response->assertRedirect(route('register'));
        $response->assertSessionHasErrors(['name', 'email', 'password']);
    }

    public function test_login_redirects_admin_and_customer_to_their_dashboards(): void
    {
        $admin = $this->admin([
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
        ]);
        $customer = $this->customer([
            'email' => 'customer@example.com',
            'password' => Hash::make('password123'),
        ]);

        $adminLogin = $this->post(route('login'), [
            'email' => $admin->email,
            'password' => 'password123',
        ]);

        $adminLogin->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);

        $this->post(route('logout'))->assertRedirect(route('home'));

        $customerLogin = $this->post(route('login'), [
            'email' => $customer->email,
            'password' => 'password123',
        ]);

        $customerLogin->assertRedirect(route('customer.orders.index'));
        $this->assertAuthenticatedAs($customer);
    }

    public function test_login_validation_and_invalid_credentials_are_reported(): void
    {
        $this->post(route('login'), [
            'email' => 'not-an-email',
            'password' => '',
        ])->assertSessionHasErrors(['email', 'password']);

        $this->customer([
            'email' => 'budi@example.com',
            'password' => Hash::make('password123'),
        ]);

        $this->from(route('login'))->post(route('login'), [
            'email' => 'budi@example.com',
            'password' => 'wrong-password',
        ])->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_guest_customer_routes_redirect_to_login(): void
    {
        $this->get(route('customer.orders.index'))->assertRedirect(route('login'));
        $this->get(route('customer.orders.create'))->assertRedirect(route('login'));
    }

    public function test_customer_cannot_access_admin_routes(): void
    {
        $customer = $this->customer();

        $this->actingAs($customer)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }
}
