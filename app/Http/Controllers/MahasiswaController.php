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
    // Ambil keyword pencarian
    $keyword = $request->keyword;

    // Ambil data dari tabel MAHASISWA (bukan users)
    $mahasiswa = Mahasiswa::when($keyword, function ($query) use ($keyword) {
            $query->where('name', 'like', "%{$keyword}%")
                  ->orWhere('nim', 'like', "%{$keyword}%")
                  ->orWhere('fakultas', 'like', "%{$keyword}%")
                  ->orWhere('jurusan', 'like', "%{$keyword}%");
        })
        ->orderBy('name') // Urutkan berdasarkan nama
        ->paginate(10)
        ->withQueryString();

    // Kirim data ke view
    return view('mahasiswa.index', [
        'mahasiswa' => $mahasiswa,
        'keyword'   => $keyword,
    ]);
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
    // Validasi data input sesuai form
    $request->validate([
        'name' => 'required|string|max:255',
        'nim' => 'required|string|max:20|unique:mahasiswa,nim', // Validasi untuk tabel mahasiswa
        'no_hp' => 'nullable|string|max:15',
        'fakultas' => 'required|string|max:255',
        'jurusan' => 'required|string|max:255',
    ]);

    // Simpan data ke tabel MAHASISWA (bukan users)
    Mahasiswa::create([
        'name' => $request->name,
        'nim' => $request->nim,
        'no_hp' => $request->no_hp,
        'fakultas' => $request->fakultas,
        'jurusan' => $request->jurusan,
    ]);

    // Redirect dengan pesan sukses
    return redirect()->route('mahasiswa.index')
        ->with('success', 'Mahasiswa berhasil ditambahkan!');
}

    /**
     * Menampilkan detail mahasiswa
     */
   public function show($id)
{
    // Cari mahasiswa dari tabel MAHASISWA
    $mahasiswa = Mahasiswa::find($id);
    
    // Jika tidak ditemukan, tampilkan error 404
    if (!$mahasiswa) {
        abort(404, 'Mahasiswa tidak ditemukan');
    }
    
    // Ambil riwayat peminjaman (jika ada relasi)
    $riwayat = $mahasiswa->peminjaman()
        ->with('barang')
        ->latest()
        ->paginate(10);
        
    // Tampilkan view detail
    return view('mahasiswa.show', compact('mahasiswa', 'riwayat'));
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
        'fakultas' => 'required|string|max:255',
        'jurusan' => 'required|string|max:255',
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
}