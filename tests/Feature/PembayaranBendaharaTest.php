<?php

namespace Tests\Feature;

use App\Models\Paket;
use App\Models\Pemesanan;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PembayaranBendaharaTest extends TestCase
{
    use RefreshDatabase;

    private function bendahara(): User
    {
        return User::factory()->create(['level' => User::ROLE_BENDAHARA]);
    }

    /**
     * @param  array<string, mixed>  $transaksiOverrides
     * @return array{0: Pemesanan, 1: Transaksi}
     */
    private function makeOrder(string $status = Transaksi::STATUS_MENUNGGU, array $transaksiOverrides = []): array
    {
        $pemesanan = Pemesanan::factory()
            ->for(User::factory()->create(['level' => User::ROLE_KONSUMEN]))
            ->for(Paket::factory())
            ->create(['harga' => 7_000_000]);

        $transaksi = Transaksi::create(array_merge([
            'user_id' => $pemesanan->user_id,
            'pemesanan_id' => $pemesanan->id,
            'status' => $status,
            'alat_pembayaran' => 'transfer',
            'bukti_pembayaran' => 'bukti/dummy.png',
            'jumlah_bayar' => 7_000_000,
            'tanggal_bayar' => now(),
        ], $transaksiOverrides));

        return [$pemesanan, $transaksi];
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('bendahara.pemesanan.index'))->assertRedirect(route('login'));
    }

    public function test_konsumen_cannot_access_bendahara_area(): void
    {
        $konsumen = User::factory()->create(['level' => User::ROLE_KONSUMEN]);

        $this->actingAs($konsumen)
            ->get(route('bendahara.pemesanan.index'))
            ->assertForbidden();
    }

    public function test_bendahara_can_view_pemesanan_list(): void
    {
        [$pemesanan] = $this->makeOrder();

        $this->actingAs($this->bendahara())
            ->get(route('bendahara.pemesanan.index'))
            ->assertOk()
            ->assertSee($pemesanan->kode_pemesanan)
            ->assertSee('Manajemen Pemesanan');
    }

    public function test_status_filter_only_shows_matching_orders(): void
    {
        [$menunggu] = $this->makeOrder(Transaksi::STATUS_MENUNGGU);
        [$valid] = $this->makeOrder(Transaksi::STATUS_VALID);

        $this->actingAs($this->bendahara())
            ->get(route('bendahara.pemesanan.index', ['status' => 'menunggu']))
            ->assertOk()
            ->assertSee($menunggu->kode_pemesanan)
            ->assertDontSee($valid->kode_pemesanan);

        $this->actingAs($this->bendahara())
            ->get(route('bendahara.pemesanan.index', ['status' => 'valid']))
            ->assertOk()
            ->assertSee($valid->kode_pemesanan)
            ->assertDontSee($menunggu->kode_pemesanan);
    }

    public function test_bendahara_can_view_order_detail_with_proof(): void
    {
        [$pemesanan] = $this->makeOrder();

        $this->actingAs($this->bendahara())
            ->get(route('bendahara.pemesanan.show', $pemesanan))
            ->assertOk()
            ->assertSee($pemesanan->kode_pemesanan)
            ->assertSee('Validasi Pembayaran')
            ->assertSee('Buka bukti pembayaran');
    }

    public function test_bendahara_can_validate_payment(): void
    {
        [$pemesanan, $transaksi] = $this->makeOrder();

        $response = $this->actingAs($this->bendahara())
            ->post(route('bendahara.pembayaran.validasi', $transaksi), [
                'keputusan' => 'valid',
                'catatan' => 'Dana sudah masuk rekening perusahaan.',
            ]);

        $response->assertRedirect(route('bendahara.pemesanan.show', $pemesanan));
        $response->assertSessionHas('success');

        $this->assertSame(Transaksi::STATUS_VALID, $transaksi->fresh()->status);
        $this->assertSame('valid', $pemesanan->fresh()->statusPembayaran());
    }

    public function test_bendahara_can_reject_payment_with_reason(): void
    {
        [$pemesanan, $transaksi] = $this->makeOrder();

        $response = $this->actingAs($this->bendahara())
            ->post(route('bendahara.pembayaran.validasi', $transaksi), [
                'keputusan' => 'ditolak',
                'catatan' => 'Nominal transfer tidak sesuai.',
            ]);

        $response->assertRedirect(route('bendahara.pemesanan.show', $pemesanan));
        $response->assertSessionHas('info');

        $transaksi->refresh();
        $this->assertSame(Transaksi::STATUS_DITOLAK, $transaksi->status);
        $this->assertSame('Nominal transfer tidak sesuai.', $transaksi->catatan);
    }

    public function test_rejection_requires_reason(): void
    {
        [, $transaksi] = $this->makeOrder();

        $this->actingAs($this->bendahara())
            ->from(route('bendahara.pemesanan.show', $transaksi->pemesanan_id))
            ->post(route('bendahara.pembayaran.validasi', $transaksi), [
                'keputusan' => 'ditolak',
                'catatan' => '',
            ])
            ->assertRedirect(route('bendahara.pemesanan.show', $transaksi->pemesanan_id))
            ->assertSessionHasErrors('catatan');

        $this->assertSame(Transaksi::STATUS_MENUNGGU, $transaksi->fresh()->status);
    }

    public function test_transaction_cannot_be_validated_twice(): void
    {
        [, $transaksi] = $this->makeOrder(Transaksi::STATUS_VALID);

        $response = $this->actingAs($this->bendahara())
            ->post(route('bendahara.pembayaran.validasi', $transaksi), [
                'keputusan' => 'ditolak',
                'catatan' => 'Coba ubah status.',
            ]);

        $response->assertSessionHas('error');
        $this->assertSame(Transaksi::STATUS_VALID, $transaksi->fresh()->status);
    }

    public function test_receipt_is_available_for_valid_payment(): void
    {
        [, $transaksi] = $this->makeOrder(Transaksi::STATUS_VALID);

        $this->actingAs($this->bendahara())
            ->get(route('bendahara.pembayaran.kuitansi', $transaksi))
            ->assertOk()
            ->assertSee('PT. PUMA STAR JAYA')
            ->assertSee('Kuitansi')
            ->assertSee('7.000.000')
            ->assertSee('Tujuh juta rupiah')
            ->assertSee('Pembayaran Valid');
    }

    public function test_receipt_is_blocked_for_pending_payment(): void
    {
        [, $transaksi] = $this->makeOrder(Transaksi::STATUS_MENUNGGU);

        $this->actingAs($this->bendahara())
            ->get(route('bendahara.pembayaran.kuitansi', $transaksi))
            ->assertRedirect(route('bendahara.pemesanan.show', $transaksi->pemesanan_id))
            ->assertSessionHas('error');
    }

    public function test_konsumen_cannot_validate_or_print_receipt(): void
    {
        [$pemesanan, $transaksi] = $this->makeOrder(Transaksi::STATUS_VALID);
        $konsumen = $pemesanan->user;

        $this->actingAs($konsumen)
            ->post(route('bendahara.pembayaran.validasi', $transaksi), ['keputusan' => 'valid'])
            ->assertForbidden();

        $this->actingAs($konsumen)
            ->get(route('bendahara.pembayaran.kuitansi', $transaksi))
            ->assertForbidden();
    }
}
