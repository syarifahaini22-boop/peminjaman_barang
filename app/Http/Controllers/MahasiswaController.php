<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;

class MahasiswaController extends Controller
{
    /**
     * Menampilkan daftar mahasiswa
     */
    public function index(Request $request)
    {
        $keyword = $request->get('keyword');

        $query = Mahasiswa::query();

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('nim', 'like', '%' . $keyword . '%')
                    ->orWhere('jurusan', 'like', '%' . $keyword . '%');
            });
        }

        $mahasiswa = $query->paginate(10);

        return view('mahasiswa.index', compact('mahasiswa', 'keyword'));
    }

    /**
     * Menampilkan form tambah mahasiswa
     */
    public function create()
    {
        return view('mahasiswa.create');
    }

    /**
     * Menyimpan mahasiswa baru
     */
    public function store(Request $request)
    {
        // Validasi data input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|max:20|unique:mahasiswa,nim',
            'no_hp' => 'required|string|max:15',
            'fakultas' => 'nullable|string|max:255',
            'jurusan' => 'nullable|string|max:255',
        ]);

        // Simpan data menggunakan validated data
        Mahasiswa::create($validated);

        return redirect()->route('mahasiswa.index')
            ->with('success', 'Mahasiswa berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail mahasiswa
     */
    public function show($id)
    {
        $mahasiswa = Mahasiswa::with(['peminjaman.barang'])->findOrFail($id);
        return view('mahasiswa.show', compact('mahasiswa'));
    }

    /**
     * Menampilkan form edit mahasiswa
     */
    public function edit($id)
    {
        // Cari mahasiswa dari tabel MAHASISWA
        $mahasiswa = Mahasiswa::find($id);

        // Jika tidak ditemukan, tampilkan error 404
        if (!$mahasiswa) {
            abort(404, 'Mahasiswa tidak ditemukan');
        }

        // Tampilkan form edit
        return view('mahasiswa.edit', compact('mahasiswa'));
    }
    /**
     * Mengupdate data mahasiswa
     */
    public function update(Request $request, $id)
    {
        // Cari mahasiswa dari tabel MAHASISWA
        $mahasiswa = Mahasiswa::find($id);

        // Jika tidak ditemukan, tampilkan error 404
        if (!$mahasiswa) {
            abort(404, 'Mahasiswa tidak ditemukan');
        }

        // Validasi data input
        $request->validate([
            'name' => 'required|string|max:255',
            'nim' => [
                'required',
                'string',
                'max:20',
                Rule::unique('mahasiswa')->ignore($mahasiswa->id) // unique untuk tabel mahasiswa
            ],
            'no_hp' => 'nullable|string|max:15',
            'fakultas' => 'nullable|string|max:255',
            'jurusan' => 'nullable|string|max:255',
        ]);

        // Update data mahasiswa
        $mahasiswa->update($request->all());

        // Redirect dengan pesan sukses
        return redirect()->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil diupdate.');
    }

    /**
     * Menghapus mahasiswa
     */
    public function destroy($id)
    {
        try {
            $mahasiswa = Mahasiswa::find($id);

            if (!$mahasiswa) {
                return redirect()->route('mahasiswa.index')
                    ->with('error', 'Mahasiswa tidak ditemukan!');
            }



            // OPSI 2: Langsung cek di tabel peminjaman (jika ada relasi user_id dengan nim)
            // $peminjamanAktif = Peminjaman::whereHas('user', function($query) use ($mahasiswa) {
            //         $query->where('nim', $mahasiswa->nim);
            //     })
            //     ->where('status', 'dipinjam')
            //     ->exists();

            // Hapus mahasiswa
            $mahasiswa->delete();



            return redirect()->route('mahasiswa.index')
                ->with('success', 'Data mahasiswa berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('mahasiswa.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function searchByNim(Request $request)
    {
        $nim = $request->get('nim');

        $mahasiswa = Mahasiswa::where('nim', $nim)->first();

        if ($mahasiswa) {
            return response()->json([
                'success' => true,
                'data' => $mahasiswa
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Mahasiswa tidak ditemukan'
        ]);
    }
}
