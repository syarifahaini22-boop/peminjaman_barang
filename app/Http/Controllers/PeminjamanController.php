<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Barang;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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

        $query = Peminjaman::with(['barang', 'mahasiswa']);

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

        $riwayat = $query->latest()->paginate(10);

        return view('peminjaman.index', compact('riwayat', 'status', 'keyword', 'start_date', 'end_date'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barang = Barang::where('status', 'tersedia')->get();
        $mahasiswa = Mahasiswa::all();

        // Generate kode peminjaman yang UNIK
        $kode_peminjaman = $this->generateKodePeminjaman();

        return view('peminjaman.create', compact('barang', 'mahasiswa', 'kode_peminjaman'));
    }

    /**
     * Generate kode peminjaman unik
     */
    private function generateKodePeminjaman()
    {
        do {
            $date = date('Ymd');
            $random = strtoupper(Str::random(4));
            $kode = "PINJ-{$date}-{$random}";

            // Cek apakah kode sudah ada
            $exists = Peminjaman::where('kode_peminjaman', $kode)->exists();
        } while ($exists);

        return $kode;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        \Log::info('Store peminjaman request:', $request->all());

        // Generate kode peminjaman JIKA tidak ada di request
        if (empty($request->kode_peminjaman)) {
            $kodePeminjaman = $this->generateKodePeminjaman();
        } else {
            $kodePeminjaman = $request->kode_peminjaman;
        }

        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswa,id',
            'tanggal_peminjaman' => 'required|date',
            'tanggal_pengembalian' => 'required|date|after_or_equal:tanggal_peminjaman',
            'tujuan_peminjaman' => 'required|string|max:255',
            'lokasi_penggunaan' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
            'barang_ids' => 'required|array|min:1',
            'barang_ids.*' => 'exists:barang,id',
            'barang.*.jumlah' => 'required|integer|min:1'
        ]);

        DB::beginTransaction();

        try {
            // Pastikan kode_peminjaman tidak kosong
            if (empty($kodePeminjaman)) {
                throw new \Exception('Kode peminjaman tidak boleh kosong');
            }

            // Buat peminjaman
            $peminjaman = Peminjaman::create([
                'kode_peminjaman' => $kodePeminjaman, // PASTIKAN ini ada nilai
                'user_id' => $request->mahasiswa_id,
                'tanggal_peminjaman' => $request->tanggal_peminjaman,
                'tanggal_pengembalian' => $request->tanggal_pengembalian,
                'tujuan_peminjaman' => $request->tujuan_peminjaman,
                'lokasi_penggunaan' => $request->lokasi_penggunaan,
                'catatan' => $request->catatan,
                'status' => 'dipinjam'
            ]);

            \Log::info('Peminjaman created:', [
                'id' => $peminjaman->id,
                'kode' => $peminjaman->kode_peminjaman
            ]);

            // Tambah barang yang dipinjam ke tabel pivot
            foreach ($request->barang_ids as $barangId) {
                $jumlah = $request->input("barang.{$barangId}.jumlah", 1);

                // Validasi stok tersedia
                $barang = Barang::findOrFail($barangId);

                if ($barang->stok < $jumlah) {
                    throw new \Exception("Stok barang {$barang->nama} tidak mencukupi. Stok tersedia: {$barang->stok}");
                }

                // Tambah ke pivot
                $peminjaman->barang()->attach($barangId, ['jumlah' => $jumlah]);

                // Update stok barang
                $barang->decrement('stok', $jumlah);

                // Update status jika stok habis
                if ($barang->stok <= 0) {
                    $barang->update(['status' => 'dipinjam']);
                }
            }

            DB::commit();

            return redirect()->route('peminjaman.index')
                ->with('success', 'Peminjaman berhasil dibuat!')
                ->with('kode', $kodePeminjaman); // Tampilkan kode ke user

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Gagal membuat peminjaman: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            return back()->withInput()
                ->with('error', 'Gagal membuat peminjaman: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $mahasiswa = Mahasiswa::with(['peminjaman.barang'])->findOrFail($id);

        return view('mahasiswa.show', compact('mahasiswa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'dipinjam') {
            return redirect()->route('peminjaman.index')
                ->with('error', 'Hanya peminjaman yang masih aktif dapat diedit.');
        }

        $barang = Barang::where('status', 'tersedia')->get();
        $mahasiswa = Mahasiswa::all();

        return view('peminjaman.edit', compact('peminjaman', 'barang', 'mahasiswa'));
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
            'mahasiswa_id' => 'required|exists:mahasiswa,id',
            'tanggal_peminjaman' => 'required|date',
            'tanggal_pengembalian' => 'required|date|after_or_equal:tanggal_peminjaman',
            'tujuan_peminjaman' => 'required|string|max:255',
            'lokasi_penggunaan' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
            'barang_ids' => 'required|array|min:1',
            'barang_ids.*' => 'exists:barang,id',
            'barang.*.jumlah' => 'required|integer|min:1'
        ]);

        DB::beginTransaction();

        try {
            // Kembalikan stok barang lama
            foreach ($peminjaman->barang as $barang) {
                $jumlahLama = $barang->pivot->jumlah;
                $barang->increment('stok', $jumlahLama);

                if ($barang->stok > 0 && $barang->status === 'dipinjam') {
                    $barang->update(['status' => 'tersedia']);
                }
            }

            // Hapus relasi barang lama
            $peminjaman->barang()->detach();

            // Update data peminjaman
            $peminjaman->update([
                'user_id' => $request->mahasiswa_id,
                'tanggal_peminjaman' => $request->tanggal_peminjaman,
                'tanggal_pengembalian' => $request->tanggal_pengembalian,
                'tujuan_peminjaman' => $request->tujuan_peminjaman,
                'lokasi_penggunaan' => $request->lokasi_penggunaan,
                'catatan' => $request->catatan,
            ]);

            // Tambah barang baru
            foreach ($request->barang_ids as $barangId) {
                $jumlah = $request->input("barang.{$barangId}.jumlah", 1);
                $barang = Barang::findOrFail($barangId);

                if ($barang->stok < $jumlah) {
                    throw new \Exception("Stok barang {$barang->nama} tidak mencukupi. Stok tersedia: {$barang->stok}");
                }

                $peminjaman->barang()->attach($barangId, ['jumlah' => $jumlah]);
                $barang->decrement('stok', $jumlah);

                if ($barang->stok <= 0) {
                    $barang->update(['status' => 'dipinjam']);
                }
            }

            DB::commit();

            return redirect()->route('peminjaman.index')
                ->with('success', 'Peminjaman berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Gagal mengupdate peminjaman: ' . $e->getMessage());
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

        DB::beginTransaction();

        try {
            // Kembalikan stok barang
            foreach ($peminjaman->barang as $barang) {
                $jumlah = $barang->pivot->jumlah;
                $barang->increment('stok', $jumlah);

                if ($barang->stok > 0 && $barang->status === 'dipinjam') {
                    $barang->update(['status' => 'tersedia']);
                }
            }

            // Hapus relasi pivot
            $peminjaman->barang()->detach();

            // Hapus peminjaman
            $peminjaman->delete();

            DB::commit();

            return redirect()->route('peminjaman.index')
                ->with('success', 'Peminjaman berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Pengembalian barang
     */
    public function kembalikan(Request $request, $id)
    {
        $peminjaman = Peminjaman::with('barang')->findOrFail($id);

        if ($peminjaman->status === 'dikembalikan') {
            return back()->with('error', 'Barang sudah dikembalikan sebelumnya.');
        }

        DB::beginTransaction();

        try {
            $tanggal_dikembalikan = now();
            $status = 'dikembalikan';

            // Cek apakah terlambat
            if ($tanggal_dikembalikan->gt($peminjaman->tanggal_pengembalian)) {
                $status = 'terlambat';
            }

            // Kembalikan stok barang
            foreach ($peminjaman->barang as $barang) {
                $jumlah = $barang->pivot->jumlah;
                $barang->increment('stok', $jumlah);

                if ($barang->stok > 0 && $barang->status === 'dipinjam') {
                    $barang->update(['status' => 'tersedia']);
                }
            }

            // Update status peminjaman
            $peminjaman->update([
                'tanggal_dikembalikan' => $tanggal_dikembalikan,
                'status' => $status,
            ]);

            DB::commit();

            return redirect()->route('peminjaman.index')
                ->with('success', 'Pengembalian barang berhasil dicatat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Riwayat Peminjaman
     */
    public function riwayat(Request $request)
    {
        $keyword = $request->get('keyword', '');
        $status = $request->get('status', '');
        $start_date = $request->get('start_date', '');
        $end_date = $request->get('end_date', '');

        $query = Peminjaman::with(['barang', 'mahasiswa']);

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

        $riwayat = $query->latest()->paginate(15);

        return view('peminjaman.riwayat', compact(
            'riwayat',
            'keyword',
            'status',
            'start_date',
            'end_date'
        ));
    }
}
