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
        $query = Barang::with('peminjamanAktif');

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
            'total' => Barang::count(),
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
    public function update(Request $request, Barang $barang)
    {
        // Validasi - HAPUS 'merek' dan 'tahun_pengadaan' jika kolom tidak ada
        $validated = $request->validate([
            'kode_barang' => 'required|max:50|unique:barang,kode_barang,' . $barang->id,
            'nama' => 'required|max:255',
            'kategori' => 'required|in:elektronik,alat_lab,buku,perlengkapan',
            'deskripsi' => 'nullable',
            // 'merek' => 'nullable|max:100', // HAPUS JIKA KOLOM TIDAK ADA
            'status' => 'required|in:tersedia,dipinjam,rusak,maintenance',
            'lokasi' => 'nullable|max:100', // Ubah required menjadi nullable
            // 'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat', // HAPUS JIKA KOLOM TIDAK ADA
            // 'tahun_pengadaan' => 'nullable|digits:4|integer|min:2000|max:' . date('Y'), // HAPUS JIKA KOLOM TIDAK ADA
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // HAPUS kolom yang tidak ada dari $validated
        unset($validated['merek']);
        unset($validated['kondisi']);
        unset($validated['tahun_pengadaan']);

        // Handle gambar upload
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($barang->gambar && Storage::exists('public/' . $barang->gambar)) {
                Storage::delete('public/' . $barang->gambar);
            }

            $image = $request->file('gambar');
            $imageName = time() . '_' . Str::slug($validated['nama']) . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/barang', $imageName);
            $validated['gambar'] = 'barang/' . $imageName;
        } else {
            // Keep existing gambar jika tidak diupdate
            $validated['gambar'] = $barang->gambar;
        }

        // Update data
        $barang->update($validated);

        return redirect()->route('barang.index')
            ->with('success', 'Data barang berhasil diperbarui!');
    }





    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barang $barang)
    {
        // Cek apakah barang sedang dipinjam
        if ($barang->status == 'dipinjam') {
            return redirect()->route('barang.index')
                ->with('error', 'Barang sedang dipinjam, tidak dapat dihapus!');
        }

        // Hapus gambar jika ada
        if ($barang->gambar && Storage::exists('public/' . $barang->gambar)) {
            Storage::delete('public/' . $barang->gambar);
        }

        // Soft delete
        $barang->delete();

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil dihapus!');
    }
}
