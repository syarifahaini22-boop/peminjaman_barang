<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Barang;
use App\Models\Mahasiswa;
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
        $status = $request->get('status');
        $keyword = $request->get('keyword', '');
        $start_date = $request->get('start_date', '');
        $end_date = $request->get('end_date', '');

        $query = Peminjaman::with(['barang', 'mahasiswa'])
            ->latest();

        // Filter keyword
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('kode_peminjaman', 'like', "%{$keyword}%")
                    ->orWhereHas('barang', function ($q2) use ($keyword) {
                        $q2->where('nama', 'like', "%{$keyword}%");
                    })
                    ->orWhereHas('mahasiswa', function ($q2) use ($keyword) {
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
            $query->whereDate('tanggal_peminjaman', '>=', $start_date);
        }

        if ($end_date) {
            $query->whereDate('tanggal_peminjaman', '<=', $end_date);
        }

        $riwayat = $query->paginate(10);

        return view('peminjaman.index', compact('riwayat', 'status', 'keyword', 'start_date', 'end_date'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Tetap ambil barang (tanpa filter stok sementara)
        $barang = Barang::all();

        // Ambil MAHASISWA untuk dipinjamkan barang (INI BENAR)
        $mahasiswa = Mahasiswa::get();

        $kode_peminjaman = 'PINJ-' . date('Ymd') . '-' . str_pad(Peminjaman::count() + 1, 4, '0', STR_PAD_LEFT);

        return view('peminjaman.create', compact('barang', 'mahasiswa', 'kode_peminjaman'));
    }





    /**
     * Store a newly created resource in storage.
     */
    // Di method store():
    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'mahasiswa_id' => 'required|exists:mahasiswa,id', // VALIDASI KE MAHASISWA
            'tanggal_peminjaman' => 'required|date',
            'tanggal_pengembalian' => 'required|date|after:tanggal_peminjaman',
            'tujuan_peminjaman' => 'required|string|max:255',
            'lokasi_penggunaan' => 'nullable|string|max:100',
            'dosen_pengampu' => 'nullable|string|max:100',
            'catatan' => 'nullable|string',
        ], [
            'mahasiswa_id.exists' => 'Mahasiswa yang dipilih tidak ditemukan',
            'barang_id.exists' => 'Barang yang dipilih tidak ditemukan',
            'tanggal_pengembalian.after' => 'Tanggal pengembalian harus setelah tanggal peminjaman',
        ]);

        try {
            DB::beginTransaction();

            // Cek apakah barang tersedia
            $barang = Barang::findOrFail($request->barang_id);

            // Cek stok barang
            if ($barang->stok < 1) {
                return back()->withErrors([
                    'barang_id' => 'Barang tidak tersedia untuk dipinjam. Stok habis.'
                ])->withInput();
            }

            // Generate kode peminjaman
            $kode_peminjaman = 'PINJ-' . date('Ymd') . '-' . str_pad(Peminjaman::count() + 1, 4, '0', STR_PAD_LEFT);

            // Buat peminjaman - user_id adalah id dari mahasiswa
            $peminjaman = Peminjaman::create([
                'barang_id' => $request->barang_id,
                'user_id' => $request->mahasiswa_id, // INI ADALAH ID DARI MAHASISWA
                'kode_peminjaman' => $kode_peminjaman,
                'tanggal_peminjaman' => $request->tanggal_peminjaman,
                'tanggal_pengembalian' => $request->tanggal_pengembalian,
                'tanggal_dikembalikan' => null,
                'status' => 'dipinjam',
                'tujuan_peminjaman' => $request->tujuan_peminjaman,
                'lokasi_penggunaan' => $request->lokasi_penggunaan,
                'dosen_pengampu' => $request->dosen_pengampu,
                'catatan' => $request->catatan,
                'kondisi_kembali' => null,
                'catatan_kembali' => null,
            ]);

            DB::commit();

            return redirect()->route('peminjaman.index')
                ->with('success', 'Peminjaman berhasil dibuat. Kode: ' . $kode_peminjaman);
        } catch (\Exception $e) {
            DB::rollBack();

            // Debug error lebih detail
            \Log::error('Error menyimpan peminjaman: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Gagal menyimpan peminjaman: ' . $e->getMessage()]);
        }
    }




    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $peminjaman = Peminjaman::with(['barang', 'mahasiswa'])->findOrFail($id);
        return view('peminjaman.show', compact('peminjaman'));
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
        $mahasiswa = Mahasiswa::get();

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

        // Validasi dengan field yang sesuai dengan form
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'mahasiswa_id' => 'required|exists:mahasiswa,id',
            'tanggal_peminjaman' => 'required|date',
            'tanggal_pengembalian' => 'required|date|after:tanggal_peminjaman',
            // HAPUS jumlah karena sudah tidak ada di form
            // 'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Karena jumlah dihapus, kita asumsikan jumlah tetap 1
            $jumlah = 1; // Default 1 karena form tidak punya field jumlah

            // Cek stok tersedia (kecuali barang yang sama)
            if ($request->barang_id != $peminjaman->barang_id) {
                $barang = Barang::findOrFail($request->barang_id);
                $stok_tersedia = $barang->stok; // Gunakan stok dari tabel barang

                if ($jumlah > $stok_tersedia) {
                    return back()->withErrors([
                        'jumlah' => 'Stok tidak mencukupi. Stok tersedia: ' . $stok_tersedia
                    ])->withInput();
                }
            } else {
                // Jika barang sama, hitung perubahan jumlah
                $barang = Barang::findOrFail($request->barang_id);
                $stok_tersedia = $barang->stok; // Stok saat ini

                // Periksa apakah jumlah baru (default 1) lebih besar dari stok
                if ($jumlah > $stok_tersedia) {
                    return back()->withErrors([
                        'jumlah' => 'Stok tidak mencukupi. Stok tersedia: ' . $stok_tersedia
                    ])->withInput();
                }
            }

            // Update data - PERHATIKAN field yang diupdate!
            $peminjaman->update([
                'barang_id' => $request->barang_id,
                'user_id' => $request->mahasiswa_id, // Tabel peminjaman punya user_id, bukan mahasiswa_id
                'tanggal_peminjaman' => $request->tanggal_peminjaman,
                'tanggal_pengembalian' => $request->tanggal_pengembalian,
                // Tidak update tanggal_kembali karena ini field untuk pengembalian aktual
                // 'jumlah' => $jumlah, // Tidak ada field jumlah di tabel peminjaman
                'catatan' => $request->keterangan, // Field di tabel adalah catatan
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
            if ($tanggal_dikembalikan->gt($peminjaman->tanggal_pengembalian)) {
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
    /**
     * Riwayat Peminjaman
     */
    public function riwayat(Request $request)
    {
        $keyword = $request->get('keyword', '');
        $status = $request->get('status', '');
        $start_date = $request->get('start_date', '');
        $end_date = $request->get('end_date', '');

        $query = Peminjaman::with(['barang', 'mahasiswa'])
            ->latest();

        // Filter keyword
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('kode_peminjaman', 'like', "%{$keyword}%")
                    ->orWhereHas('barang', function ($q2) use ($keyword) {
                        $q2->where('nama', 'like', "%{$keyword}%");
                    })
                    ->orWhereHas('mahasiswa', function ($q2) use ($keyword) {
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
            $query->whereDate('tanggal_peminjaman', '>=', $start_date);
        }

        if ($end_date) {
            $query->whereDate('tanggal_peminjaman', '<=', $end_date);
        }

        $riwayat = $query->paginate(15);  // <-- Variabel $riwayat
        return view('peminjaman.riwayat', compact(
            'riwayat',
            'keyword',
            'status',
            'start_date',
            'end_date'
        ));
    }
}
