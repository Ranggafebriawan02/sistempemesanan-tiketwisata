<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    private const LEVELS = [
        User::ROLE_ADMIN,
        User::ROLE_BENDAHARA,
        User::ROLE_DIREKTUR,
        User::ROLE_KONSUMEN,
    ];

    public function index(Request $request): View
    {
        $filter = $request->merge([
            'level' => $request->input('level') ?: 'semua',
        ])->validate([
            'level' => ['required', Rule::in(array_merge(['semua'], self::LEVELS))],
            'q' => ['nullable', 'string', 'max:100'],
        ], [
            'level.in' => 'Filter level pengguna tidak valid.',
            'q.max' => 'Kata kunci pencarian maksimal 100 karakter.',
        ]);

        $keyword = trim((string) ($filter['q'] ?? ''));

        $users = User::query()
            ->withCount('pemesanan')
            ->when(
                $filter['level'] !== 'semua',
                fn (Builder $query) => $query->where('level', $filter['level'])
            )
            ->when($keyword !== '', function (Builder $query) use ($keyword): void {
                $query->where(function (Builder $sub) use ($keyword): void {
                    $sub->where('username', 'like', "%{$keyword}%")
                        ->orWhere('nama', 'like', "%{$keyword}%")
                        ->orWhere('email', 'like', "%{$keyword}%")
                        ->orWhere('no_hp', 'like', "%{$keyword}%");
                });
            })
            ->orderByRaw("CASE level WHEN 'admin' THEN 1 WHEN 'bendahara' THEN 2 WHEN 'direktur' THEN 3 ELSE 4 END")
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        $ringkasan = User::query()
            ->selectRaw('level, COUNT(*) as total')
            ->groupBy('level')
            ->pluck('total', 'level');

        return view('admin.user.index', [
            'users' => $users,
            'filter' => $filter,
            'keyword' => $keyword,
            'ringkasan' => $ringkasan,
            'total' => User::count(),
        ]);
    }

    public function create(): View
    {
        return view('admin.user.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['aktif'] = $request->boolean('aktif');

        User::create($data);

        return redirect()
            ->route('admin.user.index')
            ->with('success', 'Akun pengguna berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        return view('admin.user.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $this->validated($request, $user);
        $aktif = $request->boolean('aktif');

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $sedangLogin = $user->is(auth()->user());

        if ($sedangLogin && $data['level'] !== User::ROLE_ADMIN) {
            return back()->withInput()->with('error', 'Anda tidak dapat mengubah level akun yang sedang digunakan.');
        }

        if ($sedangLogin && ! $aktif) {
            return back()->withInput()->with('error', 'Anda tidak dapat menonaktifkan akun yang sedang digunakan.');
        }

        if ($user->isAdmin() && $data['level'] !== User::ROLE_ADMIN && $this->jumlahAdmin($user) <= 1) {
            return back()->withInput()->with('error', 'Level akun admin terakhir tidak dapat diubah.');
        }

        if ($user->isAdmin() && ! $aktif && $this->jumlahAdminAktif($user) <= 1) {
            return back()->withInput()->with('error', 'Akun admin aktif terakhir tidak dapat dinonaktifkan.');
        }

        $data['aktif'] = $aktif;
        $user->update($data);

        return redirect()
            ->route('admin.user.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function resetPassword(User $user): RedirectResponse
    {
        $user->update(['password' => 'password']);

        return back()->with(
            'info',
            'Password akun '.$user->nama.' berhasil direset menjadi "password".'
        );
    }

    public function toggleAktif(User $user): RedirectResponse
    {
        if ($user->is(auth()->user())) {
            return back()->with('error', 'Anda tidak dapat mengubah status akun yang sedang digunakan.');
        }

        if ($user->aktif && $user->isAdmin() && $this->jumlahAdminAktif($user) <= 1) {
            return back()->with('error', 'Akun admin aktif terakhir tidak dapat dinonaktifkan.');
        }

        $user->update(['aktif' => ! $user->aktif]);

        return back()->with(
            'success',
            $user->aktif
                ? 'Akun '.$user->nama.' berhasil diaktifkan.'
                : 'Akun '.$user->nama.' berhasil dinonaktifkan.'
        );
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->is(auth()->user())) {
            return back()->with('error', 'Anda tidak dapat menghapus akun yang sedang digunakan.');
        }

        if ($user->pemesanan()->exists() || $user->transaksi()->exists()) {
            return back()->with(
                'error',
                'Akun tidak dapat dihapus karena sudah memiliki data pemesanan atau pembayaran. Nonaktifkan akun jika tidak digunakan.'
            );
        }

        if ($user->isAdmin() && $this->jumlahAdmin() <= 1) {
            return back()->with('error', 'Akun admin terakhir tidak dapat dihapus.');
        }

        $user->delete();

        return back()->with('success', 'Akun pengguna berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?User $user = null): array
    {
        return $request->validate([
            'username' => [
                'required', 'string', 'min:3', 'max:50', 'alpha_dash',
                Rule::unique('users', 'username')->ignore($user?->id),
            ],
            'nama' => ['required', 'string', 'max:100'],
            'email' => [
                'required', 'string', 'email', 'max:100',
                Rule::unique('users', 'email')->ignore($user?->id),
            ],
            'level' => ['required', Rule::in(self::LEVELS)],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:6', 'confirmed'],
            'aktif' => ['nullable', 'boolean'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.min' => 'Username minimal 3 karakter.',
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, tanda hubung, dan garis bawah.',
            'username.unique' => 'Username sudah dipakai oleh akun lain.',
            'nama.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar pada akun lain.',
            'level.required' => 'Level pengguna wajib dipilih.',
            'level.in' => 'Level pengguna tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);
    }

    private function jumlahAdmin(?User $kecuali = null): int
    {
        return User::query()
            ->where('level', User::ROLE_ADMIN)
            ->when($kecuali, fn (Builder $query) => $query->where('id', '!=', $kecuali->id))
            ->count();
    }

    private function jumlahAdminAktif(?User $kecuali = null): int
    {
        return User::query()
            ->where('level', User::ROLE_ADMIN)
            ->where('aktif', true)
            ->when($kecuali, fn (Builder $query) => $query->where('id', '!=', $kecuali->id))
            ->count();
    }
}
