<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Paket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PaketController extends Controller
{
    public function index(): View
    {
        $paket = Paket::latest()->paginate(10);

        return view('admin.paket.index', compact('paket'));
    }

    public function create(): View
    {
        return view('admin.paket.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('paket', 'public');
        }

        Paket::create($data);

        return redirect()
            ->route('admin.paket.index')
            ->with('success', 'Paket wisata berhasil ditambahkan.');
    }

    public function edit(Paket $paket): View
    {
        return view('admin.paket.edit', compact('paket'));
    }

    public function update(Request $request, Paket $paket): RedirectResponse
    {
        $data = $this->validated($request);

        if ($request->hasFile('foto')) {
            if ($paket->foto) {
                Storage::disk('public')->delete($paket->foto);
            }

            $data['foto'] = $request->file('foto')->store('paket', 'public');
        }

        $paket->update($data);

        return redirect()
            ->route('admin.paket.index')
            ->with('success', 'Paket wisata berhasil diperbarui.');
    }

    public function destroy(Paket $paket): RedirectResponse
    {
        if ($paket->pemesanan()->exists()) {
            return back()->with('error', 'Paket tidak dapat dihapus karena sudah memiliki data pemesanan.');
        }

        if ($paket->foto) {
            Storage::disk('public')->delete($paket->foto);
        }

        $paket->delete();

        return back()->with('success', 'Paket wisata berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'judul' => ['required', 'string', 'max:150'],
            'destinasi' => ['required', 'string', 'max:100'],
            'deskripsi' => ['required', 'string'],
            'fasilitas' => ['required', 'string'],
            'bus' => ['nullable', 'string', 'max:100'],
            'harga' => ['required', 'numeric', 'min:0'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'judul.required' => 'Judul paket wajib diisi.',
            'judul.max' => 'Judul paket maksimal 150 karakter.',
            'destinasi.required' => 'Destinasi wajib diisi.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'fasilitas.required' => 'Fasilitas wajib diisi.',
            'harga.required' => 'Harga wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'harga.min' => 'Harga tidak boleh negatif.',
            'foto.image' => 'Foto harus berupa gambar.',
            'foto.mimes' => 'Format foto harus JPG, JPEG, PNG, atau WEBP.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
        ]);
    }
}
