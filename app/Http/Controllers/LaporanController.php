<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Barang;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /**
     * Halaman utama laporan
     */
    public function index()
    {
        return view('laporan.index');
    }

    /**
     * Laporan Peminjaman
     */
    public function peminjaman(Request $request)
    {
        $search = $request->get('search', '');

        $query = Peminjaman::with(['barang', 'mahasiswa']);

        // Filter berdasarkan pencarian nama barang atau mahasiswa
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('barang', function ($q2) use ($search) {
                    $q2->where('nama', 'like', '%' . $search . '%');
                })
                    ->orWhereHas('mahasiswa', function ($q2) use ($search) {
                        $q2->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhere('kode_peminjaman', 'like', '%' . $search . '%');
            });
        }

        // Urutkan berdasarkan tanggal peminjaman terbaru
        $query->orderBy('tanggal_peminjaman', 'desc');

        // Gunakan paginate untuk performa lebih baik
        $peminjaman = $query->paginate(20);

        // Statistik - hitung semua data, bukan hanya yang ditampilkan di halaman
        $statsQuery = Peminjaman::query();

        if (!empty($search)) {
            $statsQuery->where(function ($q) use ($search) {
                $q->whereHas('barang', function ($q2) use ($search) {
                    $q2->where('nama', 'like', '%' . $search . '%');
                })
                    ->orWhereHas('mahasiswa', function ($q2) use ($search) {
                        $q2->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhere('kode_peminjaman', 'like', '%' . $search . '%');
            });
        }

        $stats = [
            'total' => $statsQuery->count(),
            'dipinjam' => $statsQuery->where('status', 'dipinjam')->count(),
            'dikembalikan' => $statsQuery->where('status', 'dikembalikan')->count(),
            'terlambat' => $statsQuery->where('status', 'terlambat')->count(),
        ];

        return view('laporan.peminjaman', compact(
            'peminjaman',
            'stats',
            'search'
        ));
    }

    /**
     * Laporan Pengembalian - Sederhana hanya dengan pencarian
     */
    public function pengembalian(Request $request)
    {
        // Ambil parameter pencarian
        $search = $request->get('search', '');

        // Query untuk pengembalian (status dikembalikan atau terlambat)
        $query = Peminjaman::with(['mahasiswa', 'barang'])
            ->whereIn('status', ['dikembalikan', 'terlambat']);

        // Filter pencarian (nama mahasiswa, NIM, nama barang, atau kode peminjaman)
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('mahasiswa', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%");
                })
                    ->orWhereHas('barang', function ($q2) use ($search) {
                        $q2->where('nama', 'like', "%{$search}%");
                    })
                    ->orWhere('kode_peminjaman', 'like', "%{$search}%");
            });
        }

        // Urutkan berdasarkan tanggal dikembalikan terbaru
        $query->orderBy('tanggal_dikembalikan', 'desc');

        $pengembalian = $query->paginate(20);

        // Hitung statistik
        $statQuery = Peminjaman::whereIn('status', ['dikembalikan', 'terlambat']);

        // Terapkan filter yang sama untuk statistik
        if (!empty($search)) {
            $statQuery->where(function ($q) use ($search) {
                $q->whereHas('mahasiswa', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%");
                })
                    ->orWhereHas('barang', function ($q2) use ($search) {
                        $q2->where('nama', 'like', "%{$search}%");
                    })
                    ->orWhere('kode_peminjaman', 'like', "%{$search}%");
            });
        }

        $tepat_waktu = $statQuery->where('status', 'dikembalikan')->count();
        $terlambat = $statQuery->where('status', 'terlambat')->count();

        // Hitung total barang yang dikembalikan
        $total_barang = 0;
        foreach ($pengembalian as $peminjaman) {
            foreach ($peminjaman->barang as $barang) {
                $total_barang += $barang->pivot->jumlah ?? 1;
            }
        }

        return view('pengembalian.index', compact(
            'pengembalian',
            'tepat_waktu',
            'terlambat',
            'total_barang',
            'search'
        ));
    }



    /**
     * Laporan Barang
     */
    public function barang(Request $request)
    {
        $kategori = $request->get('kategori');

        $query = Barang::withCount(['peminjaman as total_dipinjam' => function ($q) {
            $q->where('status', 'dipinjam');
        }])->withCount(['peminjaman as total_peminjaman']);

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        $barang = $query->orderBy('total_peminjaman', 'desc')->get();

        // Statistik
        $kategori_list = Barang::select('kategori', DB::raw('count(*) as total'))
            ->groupBy('kategori')
            ->get();

        $stats = [
            'total_barang' => $barang->count(),
            'total_stok' => $barang->sum('stok'),
            'total_dipinjam' => $barang->sum('total_dipinjam'),
            'kategori' => $kategori_list,
        ];

        return view('laporan.barang', compact('barang', 'stats', 'kategori'));
    }

    /**
     * Laporan Mahasiswa
     */
    public function mahasiswa()
    {
        // Gunakan model Mahasiswa, bukan User
        $mahasiswa = \App\Models\Mahasiswa::withCount(['peminjaman as total_peminjaman'])
            ->with(['peminjaman' => function ($query) {
                $query->latest()->take(5);
            }])
            ->orderBy('total_peminjaman', 'desc')
            ->get();

        // Statistik jurusan dari tabel mahasiswa
        $jurusanStats = \App\Models\Mahasiswa::select('jurusan', DB::raw('count(*) as total'))
            ->groupBy('jurusan')
            ->get();

        // Top mahasiswa berdasarkan peminjaman
        $topMahasiswa = \App\Models\Mahasiswa::withCount(['peminjaman as peminjaman_count'])
            ->orderBy('peminjaman_count', 'desc')
            ->limit(10)
            ->get();

        return view('laporan.mahasiswa', compact('mahasiswa', 'jurusanStats', 'topMahasiswa'));
    }

    /**
     * Export PDF
     */
    public function exportPdf(Request $request)
    {
        $type = $request->get('type', 'peminjaman');
        $start_date = $request->get('start_date', date('Y-m-01'));
        $end_date = $request->get('end_date', date('Y-m-d'));

        $data = [];
        $title = '';

        switch ($type) {
            case 'peminjaman':
                $data = Peminjaman::with(['barang', 'mahasiswa'])
                    ->whereBetween('tanggal_peminjaman', [$start_date, $end_date])
                    ->orderBy('tanggal_peminjaman', 'desc')
                    ->get();
                $title = 'Laporan Peminjaman Barang';
                break;

            case 'pengembalian':
                $data = Peminjaman::with(['barang', 'mahasiswa'])
                    ->whereNotNull('tanggal_dikembalikan')
                    ->whereBetween('tanggal_dikembalikan', [$start_date, $end_date])
                    ->orderBy('tanggal_dikembalikan', 'desc')
                    ->get();
                $title = 'Laporan Pengembalian Barang';
                break;
        }

        $pdf = PDF::loadView('laporan.pdf.template', [
            'data' => $data,
            'title' => $title,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);

        return $pdf->download('laporan-' . $type . '-' . date('Y-m-d') . '.pdf');
    }
}
