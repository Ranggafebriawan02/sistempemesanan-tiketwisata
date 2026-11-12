<?php

namespace Tests\Feature;

use App\Models\Paket;
use App\Models\Pemesanan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ManajemenUserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function admin(array $overrides = []): User
    {
        return User::factory()->create(array_merge([
            'level' => User::ROLE_ADMIN,
        ], $overrides));
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function payload(array $overrides = []): array
    {
        return array_merge([
            'username' => 'staffbaru',
            'nama' => 'Staff Baru',
            'email' => 'staffbaru@pumastarjaya.test',
            'level' => User::ROLE_BENDAHARA,
            'no_hp' => '081200000099',
            'alamat' => 'Kota Metro, Lampung',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
            'aktif' => '1',
        ], $overrides);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('admin.user.index'))->assertRedirect(route('login'));
        $this->get(route('admin.user.create'))->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_access_user_management(): void
    {
        $konsumen = User::factory()->create(['level' => User::ROLE_KONSUMEN]);
        $bendahara = User::factory()->create(['level' => User::ROLE_BENDAHARA]);

        $this->actingAs($konsumen)->get(route('admin.user.index'))->assertForbidden();
        $this->actingAs($bendahara)->get(route('admin.user.create'))->assertForbidden();
    }

    public function test_admin_can_view_user_list_with_filter_and_search(): void
    {
        $admin = $this->admin(['nama' => 'Admin Utama']);
        User::factory()->create([
            'level' => User::ROLE_KONSUMEN,
            'nama' => 'Konsumen Pencarian',
            'username' => 'cari_konsumen',
        ]);
        User::factory()->create([
            'level' => User::ROLE_BENDAHARA,
            'nama' => 'Bendahara Lain',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.user.index'))
            ->assertOk()
            ->assertSee('Manajemen User')
            ->assertSee('Admin Utama')
            ->assertSee('Konsumen Pencarian')
            ->assertSee('Bendahara Lain');

        $this->actingAs($admin)
            ->get(route('admin.user.index', ['level' => User::ROLE_BENDAHARA]))
            ->assertOk()
            ->assertSee('Bendahara Lain')
            ->assertDontSee('Konsumen Pencarian');

        $this->actingAs($admin)
            ->get(route('admin.user.index', ['q' => 'Pencarian']))
            ->assertOk()
            ->assertSee('Konsumen Pencarian')
            ->assertDontSee('Bendahara Lain');
    }

    public function test_admin_can_create_user(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->post(route('admin.user.store'), $this->payload())
            ->assertRedirect(route('admin.user.index'))
            ->assertSessionHas('success');

        $user = User::where('username', 'staffbaru')->firstOrFail();

        $this->assertSame('Staff Baru', $user->nama);
        $this->assertSame(User::ROLE_BENDAHARA, $user->level);
        $this->assertTrue($user->aktif);
        $this->assertTrue(Hash::check('rahasia123', $user->password));
    }

    public function test_create_user_validates_unique_fields_and_password_confirmation(): void
    {
        $admin = $this->admin();
        User::factory()->create([
            'username' => 'duplikat',
            'email' => 'duplikat@example.test',
        ]);

        $this->actingAs($admin)
            ->from(route('admin.user.create'))
            ->post(route('admin.user.store'), $this->payload([
                'username' => 'duplikat',
                'email' => 'duplikat@example.test',
                'password' => '123',
                'password_confirmation' => '456',
            ]))
            ->assertRedirect(route('admin.user.create'))
            ->assertSessionHasErrors(['username', 'email', 'password']);
    }

    public function test_admin_can_update_user_without_changing_password(): void
    {
        $admin = $this->admin();
        $user = User::factory()->create([
            'username' => 'bendahara_lama',
            'email' => 'bendahara_lama@example.test',
            'nama' => 'Bendahara Lama',
            'level' => User::ROLE_BENDAHARA,
            'password' => 'passwordlama',
        ]);

        $passwordLama = $user->password;

        $this->actingAs($admin)
            ->put(route('admin.user.update', $user), $this->payload([
                'username' => 'bendahara_baru',
                'nama' => 'Bendahara Diperbarui',
                'email' => $user->email,
                'level' => User::ROLE_DIREKTUR,
                'password' => '',
                'password_confirmation' => '',
            ]))
            ->assertRedirect(route('admin.user.index'))
            ->assertSessionHas('success');

        $user->refresh();

        $this->assertSame('bendahara_baru', $user->username);
        $this->assertSame('Bendahara Diperbarui', $user->nama);
        $this->assertSame(User::ROLE_DIREKTUR, $user->level);
        $this->assertSame($passwordLama, $user->password);
        $this->assertTrue(Hash::check('passwordlama', $user->password));
    }

    public function test_admin_can_update_user_password(): void
    {
        $admin = $this->admin();
        $user = User::factory()->create(['password' => 'passwordlama']);

        $this->actingAs($admin)
            ->put(route('admin.user.update', $user), $this->payload([
                'username' => $user->username,
                'email' => $user->email,
                'level' => $user->level,
                'password' => 'passwordbaru',
                'password_confirmation' => 'passwordbaru',
            ]))
            ->assertRedirect(route('admin.user.index'));

        $this->assertTrue(Hash::check('passwordbaru', $user->fresh()->password));
    }

    public function test_admin_can_reset_user_password(): void
    {
        $admin = $this->admin();
        $user = User::factory()->create(['password' => 'passwordlama']);

        $this->actingAs($admin)
            ->post(route('admin.user.reset-password', $user))
            ->assertSessionHas('info');

        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }

    public function test_admin_can_toggle_user_active_status(): void
    {
        $admin = $this->admin();
        $user = User::factory()->create([
            'level' => User::ROLE_KONSUMEN,
            'aktif' => true,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.user.toggle-aktif', $user))
            ->assertSessionHas('success');

        $this->assertFalse($user->fresh()->aktif);

        $this->actingAs($admin)
            ->post(route('admin.user.toggle-aktif', $user))
            ->assertSessionHas('success');

        $this->assertTrue($user->fresh()->aktif);
    }

    public function test_inactive_user_cannot_login(): void
    {
        User::factory()->create([
            'username' => 'nonaktif',
            'level' => User::ROLE_BENDAHARA,
            'aktif' => false,
        ]);

        $this->post(route('login.post'), [
            'username' => 'nonaktif',
            'password' => 'password',
        ])->assertSessionHasErrors('username');

        $this->assertGuest();
    }

    public function test_active_session_of_deactivated_user_is_terminated(): void
    {
        $user = User::factory()->create([
            'level' => User::ROLE_KONSUMEN,
            'aktif' => false,
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertRedirect(route('login'))
            ->assertSessionHas('error');

        $this->assertGuest();
    }

    public function test_admin_cannot_deactivate_or_delete_own_account(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->post(route('admin.user.toggle-aktif', $admin))
            ->assertSessionHas('error');

        $this->assertTrue($admin->fresh()->aktif);

        $this->actingAs($admin)
            ->delete(route('admin.user.destroy', $admin))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_admin_cannot_demote_own_account(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->put(route('admin.user.update', $admin), $this->payload([
                'username' => $admin->username,
                'nama' => $admin->nama,
                'email' => $admin->email,
                'level' => User::ROLE_KONSUMEN,
            ]))
            ->assertSessionHas('error');

        $this->assertSame(User::ROLE_ADMIN, $admin->fresh()->level);
    }

    public function test_user_with_pemesanan_cannot_be_deleted(): void
    {
        $admin = $this->admin();
        $konsumen = User::factory()->create(['level' => User::ROLE_KONSUMEN]);

        Pemesanan::factory()
            ->for($konsumen)
            ->for(Paket::factory())
            ->create();

        $this->actingAs($admin)
            ->delete(route('admin.user.destroy', $konsumen))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $konsumen->id]);
    }

    public function test_admin_can_delete_unused_user(): void
    {
        $admin = $this->admin();
        $user = User::factory()->create(['level' => User::ROLE_KONSUMEN]);

        $this->actingAs($admin)
            ->delete(route('admin.user.destroy', $user))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
