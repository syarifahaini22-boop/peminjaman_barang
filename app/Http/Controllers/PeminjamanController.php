<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Barang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Filter berdasarkan status
        $status = $request->get('status');
        
        $query = Peminjaman::with(['barang', 'mahasiswa'])
            ->latest();
        
        if ($status && in_array($status, ['dipinjam', 'dikembalikan', 'terlambat'])) {
            $query->where('status', $status);
        }
        
        $peminjaman = $query->paginate(10);
        
        return view('peminjaman.index', compact('peminjaman', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    // Tetap ambil barang (tanpa filter stok sementara)
    $barang = Barang::all();
    
    // Ambil MAHASISWA untuk dipinjamkan barang (INI BENAR)
    $mahasiswa = User::where('role', 'mahasiswa')->get();
    
    $kode_peminjaman = 'PINJ-' . date('Ymd') . '-' . str_pad(Peminjaman::count() + 1, 4, '0', STR_PAD_LEFT);
    
    return view('peminjaman.create', compact('barang', 'mahasiswa', 'kode_peminjaman'));
}





    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'mahasiswa_id' => 'required|exists:users,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();
            
            $barang = Barang::findOrFail($request->barang_id);
            
            // Cek stok tersedia
            $stok_tersedia = $barang->stok_tersedia;
            if ($request->jumlah > $stok_tersedia) {
                return back()->withErrors([
                    'jumlah' => 'Stok tidak mencukupi. Stok tersedia: ' . $stok_tersedia
                ])->withInput();
            }
            
            // Generate kode peminjaman
            $kode_peminjaman = 'PINJ-' . date('Ymd') . '-' . str_pad(Peminjaman::count() + 1, 4, '0', STR_PAD_LEFT);
            
            // Buat peminjaman
            $peminjaman = Peminjaman::create([
                'barang_id' => $request->barang_id,
                'mahasiswa_id' => $request->mahasiswa_id,
                'kode_peminjaman' => $kode_peminjaman,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'tanggal_kembali' => $request->tanggal_kembali,
                'status' => 'dipinjam',
                'jumlah' => $request->jumlah,
                'keterangan' => $request->keterangan,
            ]);

            DB::commit();
            
            return redirect()->route('peminjaman.index')
                ->with('success', 'Peminjaman berhasil dibuat.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }





    /**
     * Display the specified resource.
     */
    public function edit(Peminjaman $peminjaman)
{
    if ($peminjaman->status !== 'dipinjam') {
        return redirect()->route('peminjaman.index')
            ->with('error', 'Hanya peminjaman yang masih aktif dapat diedit.');
    }
    
    $barang = Barang::all();
    $mahasiswa = User::where('role', 'mahasiswa')->get();
    
    // Kirim data tambahan untuk edit
    return view('peminjaman.edit', [
        'peminjaman' => $peminjaman,
        'barang' => $barang,
        'mahasiswa' => $mahasiswa,
        'stats' => [
            'is_terlambat' => $peminjaman->is_terlambat,
        ]
    ]);
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'dipinjam') {
            return redirect()->route('peminjaman.index')
                ->with('error', 'Hanya peminjaman yang masih aktif dapat diupdate.');
        }
        
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'mahasiswa_id' => 'required|exists:users,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();
            
            // Cek stok tersedia (kecuali barang yang sama)
            if ($request->barang_id != $peminjaman->barang_id) {
                $barang = Barang::findOrFail($request->barang_id);
                $stok_tersedia = $barang->stok_tersedia;
                
                if ($request->jumlah > $stok_tersedia) {
                    return back()->withErrors([
                        'jumlah' => 'Stok tidak mencukupi. Stok tersedia: ' . $stok_tersedia
                    ])->withInput();
                }
            } else {
                // Jika barang sama, hitung perubahan jumlah
                $barang = Barang::findOrFail($request->barang_id);
                $stok_tersedia = $barang->stok_tersedia + $peminjaman->jumlah; // kembalikan dulu jumlah sebelumnya
                
                if ($request->jumlah > $stok_tersedia) {
                    return back()->withErrors([
                        'jumlah' => 'Stok tidak mencukupi. Stok tersedia: ' . ($stok_tersedia - $peminjaman->jumlah)
                    ])->withInput();
                }
            }
            
            $peminjaman->update([
                'barang_id' => $request->barang_id,
                'mahasiswa_id' => $request->mahasiswa_id,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'tanggal_kembali' => $request->tanggal_kembali,
                'jumlah' => $request->jumlah,
                'keterangan' => $request->keterangan,
            ]);
            
            DB::commit();
            
            return redirect()->route('peminjaman.index')
                ->with('success', 'Peminjaman berhasil diupdate.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'dipinjam') {
            return redirect()->route('peminjaman.index')
                ->with('error', 'Hanya peminjaman yang masih aktif dapat dihapus.');
        }
        
        try {
            DB::beginTransaction();
            
            $peminjaman->delete();
            
            DB::commit();
            
            return redirect()->route('peminjaman.index')
                ->with('success', 'Peminjaman berhasil dihapus.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Pengembalian barang
     */
    public function kembalikan(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        if ($peminjaman->status === 'dikembalikan') {
            return back()->with('error', 'Barang sudah dikembalikan sebelumnya.');
        }
        
        try {
            DB::beginTransaction();
            
            $tanggal_dikembalikan = now();
            $status = 'dikembalikan';
            
            // Cek apakah terlambat
            if ($tanggal_dikembalikan->gt($peminjaman->tanggal_kembali)) {
                $status = 'terlambat';
            }
            
            $peminjaman->update([
                'tanggal_dikembalikan' => $tanggal_dikembalikan,
                'status' => $status,
            ]);
            
            DB::commit();
            
            return redirect()->route('peminjaman.index')
                ->with('success', 'Pengembalian barang berhasil dicatat.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Riwayat Peminjaman
     */
    public function riwayat(Request $request)
    {
        $keyword = $request->get('keyword');
        $status = $request->get('status');
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        
        $query = Peminjaman::with(['barang', 'mahasiswa'])
            ->latest();
        
        // Filter keyword
        if ($keyword) {
            $query->where(function($q) use ($keyword) {
                $q->where('kode_peminjaman', 'like', "%{$keyword}%")
                  ->orWhereHas('barang', function($q2) use ($keyword) {
                      $q2->where('nama', 'like', "%{$keyword}%");
                  })
                  ->orWhereHas('mahasiswa', function($q2) use ($keyword) {
                      $q2->where('name', 'like', "%{$keyword}%");
                  });
            });
        }
        
        // Filter status
        if ($status && in_array($status, ['dipinjam', 'dikembalikan', 'terlambat'])) {
            $query->where('status', $status);
        }
        
        // Filter tanggal
        if ($start_date) {
            $query->whereDate('tanggal_pinjam', '>=', $start_date);
        }
        
        if ($end_date) {
            $query->whereDate('tanggal_pinjam', '<=', $end_date);
        }
        
        $riwayat = $query->paginate(15);
        
        return view('peminjaman.riwayat');
    }
}