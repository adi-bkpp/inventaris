<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_is_displayed(): void
    {
        $response = $this->get(route('login'));

        $response->assertOk()
            ->assertSeeText('Masuk ke akun Anda')
            ->assertSee('name="username"', false)
            ->assertSee('name="password"', false);
    }

    public function test_user_can_log_in_with_valid_credentials(): void
    {
        $user = User::factory()->create();

        $response = $this->post(route('login.store'), [
            'username' => $user->username,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_rejects_invalid_credentials(): void
    {
        $user = User::factory()->create();

        $response = $this->from(route('login'))->post(route('login.store'), [
            'username' => $user->username,
            'password' => 'incorrect-password',
        ]);

        $response->assertRedirect(route('login'))
            ->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_seeded_admin_can_log_in_with_username_and_password(): void
    {
        $this->seed(DatabaseSeeder::class);

        $response = $this->post(route('login.store'), [
            'username' => 'admin',
            'password' => 'admin',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs(User::where('username', 'admin')->firstOrFail());
        $this->assertDatabaseMissing('users', [
            'username' => 'admin',
            'password' => 'admin',
        ]);
    }

    public function test_database_seeder_does_not_create_default_accounts_in_production(): void
    {
        $this->app->detectEnvironment(fn () => 'production');

        $this->artisan('db:seed', [
            '--class' => DatabaseSeeder::class,
            '--force' => true,
        ])->assertSuccessful();

        $this->assertDatabaseMissing('users', ['username' => 'admin']);
        $this->assertDatabaseMissing('users', ['username' => 'test-user']);
    }

    public function test_inventaris_pages_require_authentication(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
        $this->get(route('category.index'))->assertRedirect(route('login'));
        $this->get(route('item.index'))->assertRedirect(route('login'));
        $this->get('/halo')->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_log_out(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('name="_token"', false)
            ->assertSeeText('Logout');

        $this->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }
}
