<?php

namespace Tests\Feature;

use App\Models\Paket;
use App\Models\Pemesanan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PaketCrudTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['level' => User::ROLE_ADMIN]);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function paketData(array $overrides = []): array
    {
        return array_merge([
            'judul' => 'Ziarah Wali Songo 7 Hari',
            'destinasi' => 'Jawa Timur & Jawa Tengah',
            'deskripsi' => 'Paket ziarah lengkap dengan pemandu berpengalaman.',
            'fasilitas' => 'Bus AC, penginapan, makan 3x sehari, pemandu wisata',
            'bus' => 'Bus Executive',
            'harga' => 3500000,
        ], $overrides);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('admin.paket.index'))->assertRedirect(route('login'));
    }

    public function test_konsumen_cannot_access_admin_paket(): void
    {
        $konsumen = User::factory()->create(['level' => User::ROLE_KONSUMEN]);

        $this->actingAs($konsumen)
            ->get(route('admin.paket.index'))
            ->assertForbidden();
    }

    public function test_admin_can_view_paket_index(): void
    {
        $paket = Paket::create($this->paketData());

        $this->actingAs($this->admin())
            ->get(route('admin.paket.index'))
            ->assertOk()
            ->assertSee($paket->judul)
            ->assertSee($paket->destinasi);
    }

    public function test_admin_can_open_create_form(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.paket.create'))
            ->assertOk()
            ->assertSee('Tambah Paket Wisata');
    }

    public function test_admin_can_create_paket_with_photo(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin())
            ->post(route('admin.paket.store'), $this->paketData([
                'foto' => $this->fakePng('paket.png'),
            ]));

        $response->assertRedirect(route('admin.paket.index'));
        $response->assertSessionHas('success');

        $paket = Paket::firstWhere('judul', 'Ziarah Wali Songo 7 Hari');

        $this->assertNotNull($paket);
        $this->assertSame('3500000.00', $paket->harga);
        $this->assertNotNull($paket->foto);
        Storage::disk('public')->assertExists($paket->foto);
    }

    public function test_store_validates_required_fields(): void
    {
        $this->actingAs($this->admin())
            ->from(route('admin.paket.create'))
            ->post(route('admin.paket.store'), [])
            ->assertRedirect(route('admin.paket.create'))
            ->assertSessionHasErrors(['judul', 'destinasi', 'deskripsi', 'fasilitas', 'harga']);

        $this->assertDatabaseCount('paket', 0);
    }

    public function test_store_rejects_non_image_photo(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin())
            ->from(route('admin.paket.create'))
            ->post(route('admin.paket.store'), $this->paketData([
                'foto' => UploadedFile::fake()->create('dokumen.pdf', 120, 'application/pdf'),
            ]))
            ->assertSessionHasErrors('foto');

        $this->assertDatabaseCount('paket', 0);
    }

    public function test_admin_can_update_paket_and_replace_photo(): void
    {
        Storage::fake('public');

        $paket = Paket::create($this->paketData());

        Storage::disk('public')->put('paket/lama.jpg', 'dummy');
        $paket->update(['foto' => 'paket/lama.jpg']);

        $response = $this->actingAs($this->admin())
            ->put(route('admin.paket.update', $paket), $this->paketData([
                'judul' => 'Ziarah Wali Songo 9 Hari',
                'harga' => 4200000,
                'foto' => $this->fakePng('baru.png'),
            ]));

        $response->assertRedirect(route('admin.paket.index'));
        $response->assertSessionHas('success');

        $paket->refresh();

        $this->assertSame('Ziarah Wali Songo 9 Hari', $paket->judul);
        $this->assertSame('4200000.00', $paket->harga);
        $this->assertNotSame('paket/lama.jpg', $paket->foto);
        Storage::disk('public')->assertMissing('paket/lama.jpg');
        Storage::disk('public')->assertExists($paket->foto);
    }

    public function test_admin_can_delete_paket_and_its_photo(): void
    {
        Storage::fake('public');

        $paket = Paket::create($this->paketData());

        Storage::disk('public')->put('paket/hapus.jpg', 'dummy');
        $paket->update(['foto' => 'paket/hapus.jpg']);

        $response = $this->actingAs($this->admin())
            ->delete(route('admin.paket.destroy', $paket));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('paket', ['id' => $paket->id]);
        Storage::disk('public')->assertMissing('paket/hapus.jpg');
    }

    public function test_paket_with_pemesanan_cannot_be_deleted(): void
    {
        $konsumen = User::factory()->create(['level' => User::ROLE_KONSUMEN]);
        $paket = Paket::create($this->paketData());

        Pemesanan::create([
            'kode_pemesanan' => 'PSJ-TEST-001',
            'user_id' => $konsumen->id,
            'paket_id' => $paket->id,
            'tanggal_berangkat' => now()->addWeek()->toDateString(),
            'jumlah_pax' => 2,
            'no_hp' => '081200000000',
            'harga' => 7000000,
        ]);

        $response = $this->actingAs($this->admin())
            ->delete(route('admin.paket.destroy', $paket));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('paket', ['id' => $paket->id]);
    }
}
