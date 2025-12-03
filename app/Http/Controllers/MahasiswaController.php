<?php

namespace App\Http\Controllers;  // Pastikan namespace ini benar

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;



class MahasiswaController extends Controller  // Pastikan nama class tepat
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $keyword = $request->get('keyword');
    
    $query = User::where('role', 'mahasiswa')->latest();
    
    if ($keyword) {
        $query->where(function($q) use ($keyword) {
            $q->where('name', 'like', "%{$keyword}%")
              ->orWhere('email', 'like', "%{$keyword}%")
              ->orWhere('nim', 'like', "%{$keyword}%")
              ->orWhere('jurusan', 'like', "%{$keyword}%");
        });
    }
    
    $mahasiswa = $query->paginate(10);
    
    // PASTIKAN INI: return view('mahasiswa.index', ...)
    return view('mahasiswa.index', compact('mahasiswa', 'keyword'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('mahasiswa.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|unique:mahasiswa,nim|max:20',
            'no_hp' => 'nullable|string|max:15',
            'fakultas' => 'required|string|max:255',
            'jurusan' => 'required|string|max:255',
        ]);

        // Simpan data
        Mahasiswa::create($request->all());

        return redirect()->route('mahasiswa.index')
            ->with('success', 'Mahasiswa berhasil ditambahkan!');
    }


    
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $mahasiswa = User::where('role', 'mahasiswa')->findOrFail($id);
        
        // Ambil riwayat peminjaman mahasiswa
        $riwayat = $mahasiswa->peminjaman()
            ->with('barang')
            ->latest()
            ->paginate(10);
            
        return view('mahasiswa.show', compact('mahasiswa', 'riwayat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
   public function edit($id)
{
    $mahasiswa = User::where('role', 'mahasiswa')->findOrFail($id);
    
    // Tambahkan statistik
    $stats = [
        'total_peminjaman' => $mahasiswa->peminjaman()->count(),
        'peminjaman_aktif' => $mahasiswa->peminjaman()->where('status', 'dipinjam')->count(),
        'dikembalikan' => $mahasiswa->peminjaman()->where('status', 'dikembalikan')->count(),
        'terlambat' => $mahasiswa->peminjaman()->where('status', 'terlambat')->count(),
    ];
    
    return view('mahasiswa.edit', compact('mahasiswa', 'stats'));
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $mahasiswa = User::where('role', 'mahasiswa')->findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('mahasiswa', 'nim')->ignore($id)

            ],
            'nim' => [
                'required',
                'string',
                'max:20',
                Rule::unique('users')->ignore($mahasiswa->id)
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'fakultas' => 'required|string|max:100',
            'jurusan' => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:15',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'nim' => $request->nim,
            'fakultas' => $request->fakultas,
            'jurusan' => $request->jurusan,
            'no_hp' => $request->no_hp,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $mahasiswa->update($data);

        return redirect()->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $mahasiswa = User::where('role', 'mahasiswa')->findOrFail($id);
        
        // Cek apakah mahasiswa masih memiliki peminjaman aktif
        $peminjamanAktif = $mahasiswa->peminjaman()
            ->where('status', 'dipinjam')
            ->exists();
            
        if ($peminjamanAktif) {
            return redirect()->route('mahasiswa.index')
                ->with('error', 'Mahasiswa masih memiliki peminjaman aktif. Tidak dapat dihapus.');
        }
        
        $mahasiswa->delete();
        
        return redirect()->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil dihapus.');
    }
}