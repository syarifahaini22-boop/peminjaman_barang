<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Query dasar dengan eager loading
        $query = Barang::with('peminjamanAktif')
            ->whereNull('deleted_at');

        // ===== SEARCH MULTI COLUMN =====
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_barang', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhere('lokasi', 'like', "%{$search}%");
                // HAPUS: ->orWhere('merek', 'like', "%{$search}%") karena kolom tidak ada
            });
        }

        // ===== FILTER KATEGORI =====
        if ($request->has('kategori') && !empty($request->kategori)) {
            $query->where('kategori', $request->kategori);
        }

        // ===== FILTER STATUS =====
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // ===== FILTER KONDISI =====
        // HAPUS atau CEK jika kolom 'kondisi' ada di database
        if ($request->has('kondisi') && !empty($request->kondisi)) {
            // Cek dulu apakah kolom kondisi ada
            // $query->where('kondisi', $request->kondisi);
        }

        // ===== FILTER TAHUN =====
        // HAPUS atau CEK jika kolom 'tahun_pengadaan' ada di database
        if ($request->has('tahun_min') && !empty($request->tahun_min)) {
            // Cek dulu apakah kolom tahun_pengadaan ada
            // $query->where('tahun_pengadaan', '>=', $request->tahun_min);
        }

        if ($request->has('tahun_max') && !empty($request->tahun_max)) {
            // Cek dulu apakah kolom tahun_pengadaan ada
            // $query->where('tahun_pengadaan', '<=', $request->tahun_max);
        }

        // ===== SORTING =====
        $sort = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');

        // Validasi field sorting - HAPUS 'tahun_pengadaan' jika tidak ada
        $allowedSorts = ['kode_barang', 'nama', 'created_at', 'updated_at'];
        // HAPUS: 'tahun_pengadaan' dari $allowedSorts jika kolom tidak ada

        if (!in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }

        $allowedOrders = ['asc', 'desc'];
        if (!in_array($order, $allowedOrders)) {
            $order = 'desc';
        }

        $query->orderBy($sort, $order);

        // ===== PAGINATION =====
        $perPage = $request->get('per_page', 10);
        $barang = $query->paginate($perPage);

        // ===== STATISTIK =====
        $stats = [
            'total' => Barang::whereNull('deleted_at')->count(),
            'tersedia' => Barang::where('status', 'tersedia')->count(),
            'dipinjam' => Barang::where('status', 'dipinjam')->count(),
            'elektronik' => Barang::where('kategori', 'elektronik')->count(),
            'alat_lab' => Barang::where('kategori', 'alat_lab')->count(),
            'buku' => Barang::where('kategori', 'buku')->count(),
            'perlengkapan' => Barang::where('kategori', 'perlengkapan')->count(),
        ];

        // HAPUS statistik 'rusak' jika kolom 'kondisi' tidak ada
        // $stats['rusak'] = Barang::where('kondisi', '!=', 'baik')->count();

        // Simpan filter untuk view
        $filters = $request->only(['search', 'kategori', 'status', 'sort', 'order', 'per_page']);
        // HAPUS: 'kondisi', 'tahun_min', 'tahun_max' dari $filters jika kolom tidak ada

        return view('barang.index', compact('barang', 'stats', 'filters'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategori = ['elektronik', 'alat_lab', 'buku', 'perlengkapan'];
        $status = ['tersedia', 'dipinjam', 'rusak', 'maintenance'];
        $kondisi = ['baik', 'rusak_ringan', 'rusak_berat'];

        return view('barang.create', compact('kategori', 'status', 'kondisi'));
    }




    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Hanya validasi kolom yang ADA di database
        $validated = $request->validate([
            'kode_barang' => 'required|unique:barang,kode_barang|max:50',
            'nama' => 'required|max:255', // Kolom ini ADA di database
            'kategori' => 'required',
            'lokasi' => 'nullable',
            'deskripsi' => 'nullable',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            // HAPUS validasi untuk kolom yang BELUM ADA di database
        ]);

        // Hanya ambil field yang ADA di database
        $dataToSave = [
            'kode_barang' => $validated['kode_barang'],
            'nama' => $validated['nama'],
            'kategori' => $validated['kategori'],
            'lokasi' => $validated['lokasi'] ?? null,
            'deskripsi' => $validated['deskripsi'] ?? null,
            // TAMBAHKAN DEFAULT VALUE UNTUK KOLOM YANG BELUM ADA
            'stok' => 0, // Default stok 0
        ];

        // Handle gambar upload
        if ($request->hasFile('gambar')) {
            $image = $request->file('gambar');
            $imageName = time() . '_' . Str::slug($validated['nama']) . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/barang', $imageName);
            $dataToSave['gambar'] = 'barang/' . $imageName;
        }

        // Simpan data HANYA dengan kolom yang ada
        Barang::create($dataToSave);

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil ditambahkan!');
    }



    /**
     * Display the specified resource.
     */
    public function show(Barang $barang)
    {
        return view('barang.show', compact('barang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Barang $barang)
    {
        $kategori = ['elektronik', 'alat_lab', 'buku', 'perlengkapan'];
        $status = ['tersedia', 'dipinjam', 'rusak', 'maintenance'];
        $kondisi = ['baik', 'rusak_ringan', 'rusak_berat'];

        return view('barang.edit', compact('barang', 'kategori', 'status', 'kondisi'));
    }




    /**
     * Update the specified resource in storage.
     */
    // BarangController.php
    public function update(Request $request, Barang $barang)
    {
        // Debug: lihat data yang masuk
        \Log::info('Update barang request:', $request->all());

        // Validasi dengan field yang sesuai dengan database
        $validated = $request->validate([
            'kode_barang' => 'required|string|max:50|unique:barang,kode_barang,' . $barang->id,
            'nama' => 'required|string|max:255', // Ganti 'nama_barang' menjadi 'nama'
            'kategori' => 'required|string|in:elektronik,alat_lab,buku,perlengkapan',
            'deskripsi' => 'nullable|string',
            'lokasi' => 'required|string|max:100',
            'status' => 'required|string|in:tersedia,dipinjam,rusak,maintenance',
            'kondisi' => 'required|string|in:baik,rusak_ringan,rusak_berat',
            'stok' => 'required|integer|min:0', // Tambahkan validasi stok
            // HAPUS 'merek' dan 'tahun_pengadaan' karena tidak ada di tabel
        ]);

        try {
            // Update data
            $barang->update($validated);

            \Log::info('Barang updated successfully: ' . $barang->id);

            return redirect()->route('barang.index')
                ->with('success', 'Data barang berhasil diperbarui!');
        } catch (\Exception $e) {
            \Log::error('Update barang error: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }





    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        \DB::beginTransaction();

        try {
            \Log::info("===== START HARD DELETE BARANG ID: {$id} =====");

            // 1. Cari barang TANPA menggunakan SoftDeletes scope
            $barang = \DB::table('barang')->where('id', $id)->first();

            if (!$barang) {
                \Log::warning("Barang dengan ID {$id} tidak ditemukan di database");
                return redirect()->route('barang.index')
                    ->with('error', 'Barang tidak ditemukan!');
            }

            \Log::info("Barang ditemukan:", [
                'id' => $barang->id,
                'nama' => $barang->nama,
                'gambar' => $barang->gambar
            ]);

            // 2. Hapus file gambar jika ada
            if ($barang->gambar && $barang->gambar !== 'NULL' && !empty(trim($barang->gambar))) {
                $gambarPath = $barang->gambar;

                // Normalisasi path
                if (strpos($gambarPath, 'barang/') === 0) {
                    $gambarPath = substr($gambarPath, 7);
                }

                \Log::info("Mencoba hapus gambar: " . $gambarPath);

                $fullPath = 'public/barang/' . $gambarPath;
                if (Storage::exists($fullPath)) {
                    Storage::delete($fullPath);
                    \Log::info("File gambar berhasil dihapus: " . $fullPath);
                } else {
                    \Log::warning("File tidak ditemukan: " . $fullPath);

                    // Coba path lain
                    $alternatePath = 'public/' . $barang->gambar;
                    if (Storage::exists($alternatePath)) {
                        Storage::delete($alternatePath);
                        \Log::info("File gambar berhasil dihapus (alternate): " . $alternatePath);
                    }
                }
            }

            // 3. HAPUS PERMANEN DARI DATABASE dengan query langsung
            $deleted = \DB::table('barang')->where('id', $id)->delete();

            if ($deleted) {
                \Log::info("SUKSES: Barang berhasil dihapus dari database. Row affected: {$deleted}");
            } else {
                \Log::error("GAGAL: Tidak ada row yang terhapus dari database");
            }

            \DB::commit();

            \Log::info("===== END HARD DELETE =====");

            return redirect()->route('barang.index')
                ->with('success', 'Barang berhasil dihapus permanen!');
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error("ERROR saat menghapus barang: " . $e->getMessage());
            \Log::error($e->getTraceAsString());

            return redirect()->route('barang.index')
                ->with('error', 'Gagal menghapus barang: ' . $e->getMessage());
        }
    }


    public function hardDelete($id)
    {
        try {
            // Nonaktifkan SoftDeletes sementara
            Barang::withoutGlobalScope('Illuminate\Database\Eloquent\SoftDeletingScope')
                ->find($id)
                ->forceDelete();

            return redirect()->route('barang.index')
                ->with('success', 'Barang berhasil dihapus permanen!');
        } catch (\Exception $e) {
            return redirect()->route('barang.index')
                ->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }


    public function checkDatabase()
    {
        echo "<h2>CEK DATABASE BARANG</h2>";

        // 1. Cek semua data
        $all = \DB::select('SELECT * FROM barang');
        echo "Total data di tabel barang: " . count($all) . "<br><br>";

        // 2. Cek struktur tabel
        echo "<h3>Struktur Tabel:</h3>";
        $structure = \DB::select("DESCRIBE barang");
        foreach ($structure as $col) {
            echo "{$col->Field} | {$col->Type} | {$col->Null} | {$col->Key}<br>";
        }

        // 3. Cek data per ID
        echo "<h3>Data per ID:</h3>";
        foreach ($all as $row) {
            echo "ID: {$row->id} | Nama: {$row->nama} | Deleted At: {$row->deleted_at}<br>";
        }

        die();
    }




    public function searchByKode(Request $request)
    {
        $kode = $request->get('kode_barang');

        $barang = Barang::where('kode_barang', $kode)
            ->where('status', 'tersedia')
            ->whereNull('deleted_at')
            ->first();

        if ($barang) {
            // Hitung stok tersedia
            $stokTersedia = $barang->stok_tersedia ?? $barang->stok;

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $barang->id,
                    'kode_barang' => $barang->kode_barang,
                    'nama' => $barang->nama,
                    'stok_tersedia' => $stokTersedia
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Barang tidak ditemukan atau tidak tersedia'
        ]);
    }
}
