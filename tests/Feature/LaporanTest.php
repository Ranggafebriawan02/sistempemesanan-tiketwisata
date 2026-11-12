<?php

namespace Tests\Feature;

use App\Models\Paket;
use App\Models\Pemesanan;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LaporanTest extends TestCase
{
    use RefreshDatabase;

    private function bendahara(string $nama = 'Sri Bendahara'): User
    {
        return User::factory()->create([
            'level' => User::ROLE_BENDAHARA,
            'nama' => $nama,
        ]);
    }

    private function direktur(string $nama = 'Didik Direktur'): User
    {
        return User::factory()->create([
            'level' => User::ROLE_DIREKTUR,
            'nama' => $nama,
        ]);
    }

    private function konsumen(): User
    {
        return User::factory()->create(['level' => User::ROLE_KONSUMEN]);
    }

    /**
     * @param  array<string, mixed>  $pemesananOverrides
     * @param  array<string, mixed>  $transaksiOverrides
     */
    private function buatPemesanan(
        int $harga = 1_000_000,
        int $pax = 2,
        ?string $status = null,
        array $pemesananOverrides = [],
        array $transaksiOverrides = []
    ): Pemesanan {
        $pemesanan = Pemesanan::factory()
            ->for($this->konsumen())
            ->for(Paket::factory())
            ->create(array_merge([
                'harga' => $harga,
                'jumlah_pax' => $pax,
            ], $pemesananOverrides));

        if ($status !== null) {
            Transaksi::create(array_merge([
                'user_id' => $pemesanan->user_id,
                'pemesanan_id' => $pemesanan->id,
                'status' => $status,
                'alat_pembayaran' => 'transfer',
                'bukti_pembayaran' => 'bukti/dummy.png',
                'jumlah_bayar' => $harga,
                'tanggal_bayar' => now(),
            ], $transaksiOverrides));
        }

        return $pemesanan;
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('laporan.pemesanan'))->assertRedirect(route('login'));
        $this->get(route('laporan.pembayaran'))->assertRedirect(route('login'));
    }

    public function test_konsumen_and_admin_cannot_access_reports(): void
    {
        $this->actingAs($this->konsumen())
            ->get(route('laporan.pemesanan'))
            ->assertForbidden();

        $admin = User::factory()->create(['level' => User::ROLE_ADMIN]);

        $this->actingAs($admin)
            ->get(route('laporan.pembayaran'))
            ->assertForbidden();
    }

    public function test_bendahara_can_view_pemesanan_report_with_summary(): void
    {
        $this->buatPemesanan(1_000_000, 2, Transaksi::STATUS_VALID, [
            'kode_pemesanan' => 'PSJ-REPORT-VALID',
        ]);
        $this->buatPemesanan(2_000_000, 3, Transaksi::STATUS_MENUNGGU, [
            'kode_pemesanan' => 'PSJ-REPORT-PENDING',
        ]);

        $this->actingAs($this->bendahara())
            ->get(route('laporan.pemesanan'))
            ->assertOk()
            ->assertSee('Laporan Pemesanan')
            ->assertSee('PSJ-REPORT-VALID')
            ->assertSee('PSJ-REPORT-PENDING')
            ->assertSee('Rp 3.000.000')
            ->assertSee('5')
            ->assertSee('Sudah Lunas');
    }

    public function test_pemesanan_report_status_filter_works(): void
    {
        $this->buatPemesanan(1_000_000, 2, Transaksi::STATUS_VALID, [
            'kode_pemesanan' => 'PSJ-REPORT-VALID',
        ]);
        $this->buatPemesanan(1_000_000, 2, Transaksi::STATUS_MENUNGGU, [
            'kode_pemesanan' => 'PSJ-REPORT-PENDING',
        ]);

        $this->actingAs($this->bendahara())
            ->get(route('laporan.pemesanan', ['status' => 'valid']))
            ->assertOk()
            ->assertSee('PSJ-REPORT-VALID')
            ->assertDontSee('PSJ-REPORT-PENDING');
    }

    public function test_pemesanan_report_respects_period_filter(): void
    {
        $didalam = $this->buatPemesanan(1_000_000, 2, null, [
            'kode_pemesanan' => 'PSJ-PERIODE-INI',
        ]);

        $diluar = $this->buatPemesanan(1_000_000, 2, null, [
            'kode_pemesanan' => 'PSJ-PERIODE-LALU',
        ]);

        DB::table('pemesanan')
            ->where('id', $diluar->id)
            ->update(['created_at' => Carbon::now()->subMonthsNoOverflow(2)]);

        $this->actingAs($this->bendahara())
            ->get(route('laporan.pemesanan'))
            ->assertOk()
            ->assertSee($didalam->kode_pemesanan)
            ->assertDontSee($diluar->kode_pemesanan);
    }

    public function test_pemesanan_report_rejects_invalid_period(): void
    {
        $this->actingAs($this->bendahara())
            ->from(route('laporan.pemesanan'))
            ->get(route('laporan.pemesanan', [
                'dari' => now()->toDateString(),
                'sampai' => now()->subDay()->toDateString(),
            ]))
            ->assertRedirect(route('laporan.pemesanan'))
            ->assertSessionHasErrors('sampai');
    }

    public function test_bendahara_can_print_pemesanan_report(): void
    {
        $bendahara = $this->bendahara('Sri Bendahara');
        $this->direktur('Didik Direktur');

        $this->buatPemesanan(1_500_000, 2, Transaksi::STATUS_VALID, [
            'kode_pemesanan' => 'PSJ-CETAK-001',
        ]);

        $this->actingAs($bendahara)
            ->get(route('laporan.pemesanan.cetak'))
            ->assertOk()
            ->assertSee('PT. PUMA STAR JAYA')
            ->assertSee('Laporan Pemesanan')
            ->assertSee('PSJ-CETAK-001')
            ->assertSee('Sri Bendahara')
            ->assertSee('Didik Direktur')
            ->assertSee('Cetak Laporan');
    }

    public function test_bendahara_can_view_pembayaran_report_with_totals(): void
    {
        $this->buatPemesanan(7_000_000, 2, Transaksi::STATUS_VALID, [
            'kode_pemesanan' => 'PSJ-BAYAR-VALID',
        ]);
        $this->buatPemesanan(1_500_000, 1, Transaksi::STATUS_MENUNGGU, [
            'kode_pemesanan' => 'PSJ-BAYAR-PENDING',
        ]);

        $this->actingAs($this->bendahara())
            ->get(route('laporan.pembayaran'))
            ->assertOk()
            ->assertSee('Laporan Pembayaran')
            ->assertSee('PSJ-BAYAR-VALID')
            ->assertSee('PSJ-BAYAR-PENDING')
            ->assertSee('Rp 7.000.000')
            ->assertSee('Rp 1.500.000')
            ->assertSee('Diterima (Valid)');
    }

    public function test_pembayaran_report_status_filter_and_period_work(): void
    {
        $valid = $this->buatPemesanan(1_000_000, 1, Transaksi::STATUS_VALID, [
            'kode_pemesanan' => 'PSJ-BAYAR-VALID',
        ]);
        $menunggu = $this->buatPemesanan(1_000_000, 1, Transaksi::STATUS_MENUNGGU, [
            'kode_pemesanan' => 'PSJ-BAYAR-PENDING',
        ]);
        $diluar = $this->buatPemesanan(1_000_000, 1, Transaksi::STATUS_VALID, [
            'kode_pemesanan' => 'PSJ-BAYAR-LALU',
        ]);

        DB::table('transaksi')
            ->where('pemesanan_id', $diluar->id)
            ->update(['tanggal_bayar' => Carbon::now()->subMonthsNoOverflow(2)]);

        $this->actingAs($this->bendahara())
            ->get(route('laporan.pembayaran', ['status' => 'valid']))
            ->assertOk()
            ->assertSee($valid->kode_pemesanan)
            ->assertDontSee($menunggu->kode_pemesanan)
            ->assertDontSee($diluar->kode_pemesanan);
    }

    public function test_direktur_can_view_and_print_reports(): void
    {
        $this->buatPemesanan(2_000_000, 2, Transaksi::STATUS_VALID, [
            'kode_pemesanan' => 'PSJ-DIREKTUR-01',
        ]);

        $direktur = $this->direktur();

        $this->actingAs($direktur)
            ->get(route('laporan.pemesanan'))
            ->assertOk()
            ->assertSee('PSJ-DIREKTUR-01')
            ->assertSee('Panel Direktur');

        $this->actingAs($direktur)
            ->get(route('laporan.pembayaran'))
            ->assertOk()
            ->assertSee('PSJ-DIREKTUR-01');

        $this->actingAs($direktur)
            ->get(route('laporan.pembayaran.cetak'))
            ->assertOk()
            ->assertSee('PT. PUMA STAR JAYA')
            ->assertSee('Laporan Pembayaran');
    }
}
