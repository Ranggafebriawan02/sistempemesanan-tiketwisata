<?php

namespace Tests\Feature;

use App\Models\Kupon;
use App\Models\Paket;
use App\Models\Pemesanan;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PemesananKonsumenTest extends TestCase
{
    use RefreshDatabase;

    private function konsumen(): User
    {
        return User::factory()->create(['level' => User::ROLE_KONSUMEN]);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function bookingData(array $overrides = []): array
    {
        return array_merge([
            'tanggal_berangkat' => now()->addWeek()->toDateString(),
            'jumlah_pax' => 3,
            'nama_rombongan' => 'Keluarga Budi',
            'no_hp' => '081234567890',
            'catatan' => 'Jemput di Kota Metro',
        ], $overrides);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('konsumen.pemesanan.index'))->assertRedirect(route('login'));
    }

    public function test_admin_cannot_access_consumer_orders(): void
    {
        $admin = User::factory()->create(['level' => User::ROLE_ADMIN]);

        $this->actingAs($admin)
            ->get(route('konsumen.pemesanan.index'))
            ->assertForbidden();
    }

    public function test_konsumen_can_open_booking_form(): void
    {
        $paket = Paket::factory()->create();

        $this->actingAs($this->konsumen())
            ->get(route('konsumen.pemesanan.create', $paket))
            ->assertOk()
            ->assertSee('Formulir Pemesanan')
            ->assertSee($paket->judul);
    }

    public function test_konsumen_can_create_booking(): void
    {
        $konsumen = $this->konsumen();
        $paket = Paket::factory()->create(['harga' => 1_500_000]);

        $response = $this->actingAs($konsumen)
            ->post(route('konsumen.pemesanan.store', $paket), $this->bookingData());

        $pemesanan = Pemesanan::first();

        $this->assertNotNull($pemesanan);
        $response->assertRedirect(route('konsumen.pemesanan.bayar', $pemesanan));
        $response->assertSessionHas('success');

        $this->assertSame($konsumen->id, $pemesanan->user_id);
        $this->assertSame($paket->id, $pemesanan->paket_id);
        $this->assertSame(3, $pemesanan->jumlah_pax);
        $this->assertSame('4500000.00', $pemesanan->harga);
        $this->assertMatchesRegularExpression('/^PSJ-\d{8}-[A-Z0-9]{4}$/', $pemesanan->kode_pemesanan);
    }

    public function test_booking_validates_required_fields_and_date(): void
    {
        $paket = Paket::factory()->create();

        $this->actingAs($this->konsumen())
            ->from(route('konsumen.pemesanan.create', $paket))
            ->post(route('konsumen.pemesanan.store', $paket), [
                'tanggal_berangkat' => now()->subDay()->toDateString(),
                'jumlah_pax' => 0,
                'no_hp' => '',
            ])
            ->assertRedirect(route('konsumen.pemesanan.create', $paket))
            ->assertSessionHasErrors(['tanggal_berangkat', 'jumlah_pax', 'no_hp']);

        $this->assertDatabaseCount('pemesanan', 0);
    }

    public function test_kupon_is_applied_and_marked_as_used(): void
    {
        $konsumen = $this->konsumen();
        $paket = Paket::factory()->create(['harga' => 1_000_000]);

        $kupon = Kupon::create([
            'user_id' => null,
            'kode' => 'HEMAT50',
            'potongan' => 50_000,
        ]);

        $this->actingAs($konsumen)
            ->post(route('konsumen.pemesanan.store', $paket), $this->bookingData([
                'jumlah_pax' => 2,
                'kode_kupon' => 'hemat50',
            ]))
            ->assertSessionHasNoErrors();

        $pemesanan = Pemesanan::first();

        $this->assertSame('1950000.00', $pemesanan->harga);
        $this->assertSame($kupon->id, $pemesanan->kupon_id);
        $this->assertTrue($kupon->fresh()->digunakan);
    }

    public function test_invalid_kupon_is_rejected(): void
    {
        $paket = Paket::factory()->create();

        $this->actingAs($this->konsumen())
            ->from(route('konsumen.pemesanan.create', $paket))
            ->post(route('konsumen.pemesanan.store', $paket), $this->bookingData([
                'kode_kupon' => 'TIDAKADA',
            ]))
            ->assertSessionHasErrors('kode_kupon');

        $this->assertDatabaseCount('pemesanan', 0);
    }

    public function test_konsumen_can_upload_payment_proof(): void
    {
        Storage::fake('public');

        $konsumen = $this->konsumen();
        $pemesanan = Pemesanan::factory()
            ->for($konsumen)
            ->for(Paket::factory())
            ->create(['harga' => 2_000_000]);

        $response = $this->actingAs($konsumen)
            ->post(route('konsumen.pemesanan.storePembayaran', $pemesanan), [
                'alat_pembayaran' => 'transfer',
                'jumlah_bayar' => 2_000_000,
                'tanggal_bayar' => now()->toDateString(),
                'bukti_pembayaran' => $this->fakePng('bukti.png'),
                'catatan' => 'Transfer via BRI',
            ]);

        $transaksi = Transaksi::first();

        $response->assertRedirect(route('konsumen.pemesanan.show', $pemesanan));
        $response->assertSessionHas('success');

        $this->assertNotNull($transaksi);
        $this->assertSame($pemesanan->id, $transaksi->pemesanan_id);
        $this->assertSame($konsumen->id, $transaksi->user_id);
        $this->assertSame(Transaksi::STATUS_MENUNGGU, $transaksi->status);
        Storage::disk('public')->assertExists($transaksi->bukti_pembayaran);
        $this->assertSame('menunggu', $pemesanan->fresh()->statusPembayaran());
    }

    public function test_payment_proof_is_required(): void
    {
        $konsumen = $this->konsumen();
        $pemesanan = Pemesanan::factory()->for($konsumen)->for(Paket::factory())->create();

        $this->actingAs($konsumen)
            ->post(route('konsumen.pemesanan.storePembayaran', $pemesanan), [
                'alat_pembayaran' => 'transfer',
                'jumlah_bayar' => 100_000,
                'tanggal_bayar' => now()->toDateString(),
            ])
            ->assertSessionHasErrors('bukti_pembayaran');

        $this->assertDatabaseCount('transaksi', 0);
    }

    public function test_konsumen_cannot_view_another_users_order(): void
    {
        $pemesanan = Pemesanan::factory()
            ->for(User::factory()->create(['level' => User::ROLE_KONSUMEN]))
            ->for(Paket::factory())
            ->create();

        $this->actingAs($this->konsumen())
            ->get(route('konsumen.pemesanan.show', $pemesanan))
            ->assertForbidden();
    }

    public function test_konsumen_cannot_pay_another_users_order(): void
    {
        Storage::fake('public');

        $pemesanan = Pemesanan::factory()
            ->for(User::factory()->create(['level' => User::ROLE_KONSUMEN]))
            ->for(Paket::factory())
            ->create();

        $this->actingAs($this->konsumen())
            ->post(route('konsumen.pemesanan.storePembayaran', $pemesanan), [
                'alat_pembayaran' => 'transfer',
                'jumlah_bayar' => 100_000,
                'tanggal_bayar' => now()->toDateString(),
                'bukti_pembayaran' => $this->fakePng(),
            ])
            ->assertForbidden();

        $this->assertDatabaseCount('transaksi', 0);
    }

    public function test_payment_waiting_validation_cannot_be_uploaded_again(): void
    {
        Storage::fake('public');

        $konsumen = $this->konsumen();
        $pemesanan = Pemesanan::factory()->for($konsumen)->for(Paket::factory())->create();

        Transaksi::create([
            'user_id' => $konsumen->id,
            'pemesanan_id' => $pemesanan->id,
            'status' => Transaksi::STATUS_MENUNGGU,
            'alat_pembayaran' => 'transfer',
            'jumlah_bayar' => 1_000_000,
            'tanggal_bayar' => now(),
        ]);

        $this->actingAs($konsumen)
            ->get(route('konsumen.pemesanan.bayar', $pemesanan))
            ->assertRedirect(route('konsumen.pemesanan.show', $pemesanan))
            ->assertSessionHas('info');

        $this->actingAs($konsumen)
            ->post(route('konsumen.pemesanan.storePembayaran', $pemesanan), [
                'alat_pembayaran' => 'transfer',
                'jumlah_bayar' => 1_000_000,
                'tanggal_bayar' => now()->toDateString(),
                'bukti_pembayaran' => $this->fakePng(),
            ])
            ->assertRedirect(route('konsumen.pemesanan.show', $pemesanan))
            ->assertSessionHas('info');

        $this->assertDatabaseCount('transaksi', 1);
    }

    public function test_rejected_payment_allows_reupload(): void
    {
        Storage::fake('public');

        $konsumen = $this->konsumen();
        $pemesanan = Pemesanan::factory()->for($konsumen)->for(Paket::factory())->create(['harga' => 1_000_000]);

        Transaksi::create([
            'user_id' => $konsumen->id,
            'pemesanan_id' => $pemesanan->id,
            'status' => Transaksi::STATUS_DITOLAK,
            'alat_pembayaran' => 'transfer',
            'jumlah_bayar' => 1_000_000,
            'tanggal_bayar' => now(),
            'catatan' => 'Bukti tidak jelas',
        ]);

        $this->actingAs($konsumen)
            ->get(route('konsumen.pemesanan.bayar', $pemesanan))
            ->assertOk()
            ->assertSee('Pembayaran sebelumnya ditolak')
            ->assertSee('Bukti tidak jelas');

        $this->actingAs($konsumen)
            ->post(route('konsumen.pemesanan.storePembayaran', $pemesanan), [
                'alat_pembayaran' => 'transfer',
                'jumlah_bayar' => 1_000_000,
                'tanggal_bayar' => now()->toDateString(),
                'bukti_pembayaran' => $this->fakePng('bukti-baru.png'),
            ])
            ->assertRedirect(route('konsumen.pemesanan.show', $pemesanan))
            ->assertSessionHas('success');

        $this->assertDatabaseCount('transaksi', 2);
        $this->assertSame('menunggu', $pemesanan->fresh()->statusPembayaran());
    }

    public function test_index_only_shows_own_orders(): void
    {
        $konsumen = $this->konsumen();

        $milikSendiri = Pemesanan::factory()
            ->for($konsumen)
            ->for(Paket::factory()->state(['judul' => 'Paket Milik Sendiri']))
            ->create();

        $milikOrangLain = Pemesanan::factory()
            ->for(User::factory()->create(['level' => User::ROLE_KONSUMEN]))
            ->for(Paket::factory()->state(['judul' => 'Paket Orang Lain']))
            ->create();

        $this->actingAs($konsumen)
            ->get(route('konsumen.pemesanan.index'))
            ->assertOk()
            ->assertSee($milikSendiri->kode_pemesanan)
            ->assertDontSee($milikOrangLain->kode_pemesanan);
    }
}
