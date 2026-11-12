<?php

namespace App\Http\Controllers\Konsumen;

use App\Http\Controllers\Controller;
use App\Models\Kupon;
use App\Models\Paket;
use App\Models\Pemesanan;
use App\Models\Transaksi;
use App\Services\MidtransService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PemesananController extends Controller
{
    public function index(): View
    {
        $pemesanan = Pemesanan::with(['paket', 'transaksiTerakhir'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(8);

        return view('konsumen.pemesanan.index', compact('pemesanan'));
    }

    public function create(Paket $paket): View
    {
        return view('konsumen.pemesanan.create', compact('paket'));
    }

    public function store(Request $request, Paket $paket): RedirectResponse
    {
        $data = $request->validate([
            'tanggal_berangkat' => ['required', 'date', 'after_or_equal:today'],
            'jumlah_pax' => ['required', 'integer', 'min:1', 'max:200'],
            'nama_rombongan' => ['nullable', 'string', 'max:150'],
            'no_hp' => ['required', 'string', 'max:20'],
            'catatan' => ['nullable', 'string', 'max:1000'],
            'kode_kupon' => ['nullable', 'string', 'max:50'],
        ], [
            'tanggal_berangkat.required' => 'Tanggal berangkat wajib diisi.',
            'tanggal_berangkat.date' => 'Tanggal berangkat tidak valid.',
            'tanggal_berangkat.after_or_equal' => 'Tanggal berangkat tidak boleh sebelum hari ini.',
            'jumlah_pax.required' => 'Jumlah peserta wajib diisi.',
            'jumlah_pax.integer' => 'Jumlah peserta harus berupa angka bulat.',
            'jumlah_pax.min' => 'Jumlah peserta minimal 1 orang.',
            'jumlah_pax.max' => 'Jumlah peserta maksimal 200 orang.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'no_hp.max' => 'Nomor HP maksimal 20 karakter.',
        ]);

        $total = (float) $paket->harga * (int) $data['jumlah_pax'];

        $kupon = null;

        if (! empty($data['kode_kupon'])) {
            $kupon = $this->findKupon($data['kode_kupon']);

            if (! $kupon) {
                throw ValidationException::withMessages([
                    'kode_kupon' => 'Kode kupon tidak valid, sudah digunakan, atau bukan milik Anda.',
                ]);
            }

            $total = max(0, $total - (float) $kupon->potongan);
        }

        $pemesanan = Pemesanan::create([
            'kode_pemesanan' => $this->generateKode(),
            'user_id' => auth()->id(),
            'paket_id' => $paket->id,
            'kupon_id' => $kupon?->id,
            'tanggal_berangkat' => $data['tanggal_berangkat'],
            'jumlah_pax' => $data['jumlah_pax'],
            'nama_rombongan' => $data['nama_rombongan'] ?? null,
            'no_hp' => $data['no_hp'],
            'harga' => $total,
            'catatan' => $data['catatan'] ?? null,
        ]);

        if ($kupon) {
            $kupon->update(['digunakan' => true]);
        }

        return redirect()
            ->route('konsumen.pemesanan.bayar', $pemesanan)
            ->with('success', 'Pemesanan berhasil dibuat. Silakan selesaikan pembayaran.');
    }

    public function show(Pemesanan $pemesanan, MidtransService $midtrans): View
    {
        $this->ensureOwner($pemesanan);

        $pemesanan->load(['paket', 'transaksi', 'kupon']);

        $snapToken = null;
        if ($pemesanan->bisaBayar()) {
            try {
                $snapToken = $midtrans->getSnapToken($pemesanan);
            } catch (\Exception $e) {
                \Log::warning('Midtrans Snap Token generation error: ' . $e->getMessage());
            }
        }

        return view('konsumen.pemesanan.show', compact('pemesanan', 'snapToken'));
    }

    public function bayar(Pemesanan $pemesanan, MidtransService $midtrans): View|RedirectResponse
    {
        $this->ensureOwner($pemesanan);

        if (! $pemesanan->bisaBayar()) {
            return redirect()
                ->route('konsumen.pemesanan.show', $pemesanan)
                ->with('info', 'Pembayaran untuk pesanan ini sudah dikirim dan sedang menunggu validasi bendahara.');
        }

        $snapToken = null;
        try {
            $snapToken = $midtrans->getSnapToken($pemesanan);
        } catch (\Exception $e) {
            \Log::warning('Midtrans Snap Token generation error in bayar: ' . $e->getMessage());
        }

        $pemesanan->load(['paket', 'transaksi', 'transaksiTerakhir']);

        return view('konsumen.pemesanan.bayar', compact('pemesanan', 'snapToken'));
    }

    public function storePembayaran(Request $request, Pemesanan $pemesanan): RedirectResponse
    {
        $this->ensureOwner($pemesanan);

        if (! $pemesanan->bisaBayar()) {
            return redirect()
                ->route('konsumen.pemesanan.show', $pemesanan)
                ->with('info', 'Pembayaran untuk pesanan ini sudah dikirim dan sedang menunggu validasi bendahara.');
        }

        $data = $request->validate([
            'alat_pembayaran' => ['required', 'in:transfer,tunai'],
            'jumlah_bayar' => ['required', 'numeric', 'min:1'],
            'tanggal_bayar' => ['required', 'date'],
            'bukti_pembayaran' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:2048'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ], [
            'alat_pembayaran.required' => 'Pilih alat pembayaran.',
            'alat_pembayaran.in' => 'Alat pembayaran tidak valid.',
            'jumlah_bayar.required' => 'Jumlah bayar wajib diisi.',
            'jumlah_bayar.numeric' => 'Jumlah bayar harus berupa angka.',
            'jumlah_bayar.min' => 'Jumlah bayar minimal Rp 1.',
            'tanggal_bayar.required' => 'Tanggal bayar wajib diisi.',
            'tanggal_bayar.date' => 'Tanggal bayar tidak valid.',
            'bukti_pembayaran.required' => 'Bukti pembayaran wajib diunggah.',
            'bukti_pembayaran.mimes' => 'Bukti pembayaran harus berformat JPG, JPEG, PNG, WEBP, atau PDF.',
            'bukti_pembayaran.max' => 'Ukuran bukti pembayaran maksimal 2 MB.',
        ]);

        $path = $request->file('bukti_pembayaran')->store('bukti', 'public');

        Transaksi::create([
            'user_id' => auth()->id(),
            'pemesanan_id' => $pemesanan->id,
            'status' => Transaksi::STATUS_MENUNGGU,
            'alat_pembayaran' => $data['alat_pembayaran'],
            'bukti_pembayaran' => $path,
            'jumlah_bayar' => $data['jumlah_bayar'],
            'tanggal_bayar' => $data['tanggal_bayar'],
            'catatan' => $data['catatan'] ?? null,
        ]);

        return redirect()
            ->route('konsumen.pemesanan.show', $pemesanan)
            ->with('success', 'Bukti pembayaran berhasil diunggah. Pembayaran akan divalidasi oleh bendahara.');
    }

    public function syncMidtrans(Request $request, Pemesanan $pemesanan): \Illuminate\Http\JsonResponse
    {
        $this->ensureOwner($pemesanan);

        $result = $request->input('result', []);
        $transactionStatus = $result['transaction_status'] ?? null;
        $paymentType = $result['payment_type'] ?? 'midtrans';
        $grossAmount = isset($result['gross_amount']) ? (float) $result['gross_amount'] : (float) $pemesanan->harga;

        $status = Transaksi::STATUS_MENUNGGU;
        if (in_array($transactionStatus, ['settlement', 'capture'])) {
            $status = Transaksi::STATUS_VALID;
        } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
            $status = Transaksi::STATUS_DITOLAK;
        } elseif ($transactionStatus === 'pending') {
            $status = Transaksi::STATUS_MENUNGGU;
        }

        Transaksi::updateOrCreate(
            [
                'pemesanan_id' => $pemesanan->id,
                'alat_pembayaran' => 'midtrans (' . $paymentType . ')',
            ],
            [
                'user_id' => $pemesanan->user_id,
                'status' => $status,
                'jumlah_bayar' => $grossAmount,
                'tanggal_bayar' => now(),
                'catatan' => 'Pembayaran via Midtrans (' . $paymentType . ' - ' . ($transactionStatus ?? 'sukses') . ')',
            ]
        );

        return response()->json(['success' => true]);
    }

    private function ensureOwner(Pemesanan $pemesanan): void
    {
        abort_unless($pemesanan->user_id === auth()->id(), 403, 'Anda tidak memiliki akses ke pesanan ini.');
    }

    private function findKupon(string $kode): ?Kupon
    {
        return Kupon::where('kode', strtoupper(trim($kode)))
            ->where('digunakan', false)
            ->where(function ($query) {
                $query->whereNull('user_id')->orWhere('user_id', auth()->id());
            })
            ->first();
    }

    private function generateKode(): string
    {
        do {
            $kode = 'PSJ-'.now()->format('Ymd').'-'.strtoupper(Str::random(4));
        } while (Pemesanan::where('kode_pemesanan', $kode)->exists());

        return $kode;
    }
}
