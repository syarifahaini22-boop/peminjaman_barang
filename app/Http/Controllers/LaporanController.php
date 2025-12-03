<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Barang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PDF;

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
        $start_date = $request->get('start_date', date('Y-m-01'));
        $end_date = $request->get('end_date', date('Y-m-d'));
        $status = $request->get('status');
        
        $query = Peminjaman::with(['barang', 'mahasiswa'])
            ->whereBetween('tanggal_peminjaman', [$start_date, $end_date]);
        
        if ($status && in_array($status, ['dipinjam', 'dikembalikan', 'terlambat'])) {
            $query->where('status', $status);
        }
        
        $peminjaman = $query->orderBy('tanggal_peminjaman', 'desc')->get();
        
        // Statistik
        $stats = [
            'total' => $peminjaman->count(),
            'dipinjam' => $peminjaman->where('status', 'dipinjam')->count(),
            'dikembalikan' => $peminjaman->where('status', 'dikembalikan')->count(),
            'terlambat' => $peminjaman->where('status', 'terlambat')->count(),
            'total_barang' => $peminjaman->sum('jumlah'),
        ];
        
        return view('laporan.peminjaman', compact(
            'peminjaman', 'stats', 'start_date', 'end_date', 'status'
        ));
    }

    /**
     * Laporan Pengembalian
     */
    public function pengembalian(Request $request)
    {
        $start_date = $request->get('start_date', date('Y-m-01'));
        $end_date = $request->get('end_date', date('Y-m-d'));
        
        $pengembalian = Peminjaman::with(['barang', 'mahasiswa'])
            ->whereNotNull('tanggal_dikembalikan')
            ->whereBetween('tanggal_dikembalikan', [$start_date, $end_date])
            ->orderBy('tanggal_dikembalikan', 'desc')
            ->get();
        
        // Statistik pengembalian terlambat
        $terlambat = $pengembalian->where('status', 'terlambat')->count();
        $tepat_waktu = $pengembalian->where('status', 'dikembalikan')->count();
        
        $stats = [
            'total' => $pengembalian->count(),
            'terlambat' => $terlambat,
            'tepat_waktu' => $tepat_waktu,
            'persentase_terlambat' => $pengembalian->count() > 0 
                ? round(($terlambat / $pengembalian->count()) * 100, 2) 
                : 0,
        ];
        
        return view('laporan.pengembalian', compact(
            'pengembalian', 'stats', 'start_date', 'end_date'
        ));
    }

    /**
     * Laporan Barang
     */
    public function barang(Request $request)
    {
        $kategori = $request->get('kategori');
        
        $query = Barang::withCount(['peminjaman as total_dipinjam' => function($q) {
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
    // Di App\Http\Controllers\LaporanController.php

public function mahasiswa()
{
    $mahasiswa = User::where('role', 'mahasiswa')
        ->withCount(['peminjaman as total_peminjaman'])
        ->with(['peminjaman' => function($query) {
            $query->latest()->take(5);
        }])
        ->orderBy('total_peminjaman', 'desc')
        ->get();

    $jurusanStats = User::where('role', 'mahasiswa')
        ->select('jurusan', \DB::raw('count(*) as total'))
        ->groupBy('jurusan')
        ->get();

    $topMahasiswa = User::where('role', 'mahasiswa')
        ->withCount(['peminjaman as peminjaman_count'])
        ->orderBy('peminjaman_count', 'desc')
        ->limit(10)
        ->get();

    return view('laporan.mahasiswa', compact('mahasiswa', 'jurusanStats', 'topMahasiswa'));
}
    /**
     * Export Excel
     */
    public function exportExcel(Request $request)
    {
        $type = $request->get('type', 'peminjaman');
        $start_date = $request->get('start_date', date('Y-m-01'));
        $end_date = $request->get('end_date', date('Y-m-d'));
        
        // Untuk sementara, redirect ke view dulu
        // Nanti bisa diintegrasikan dengan Maatwebsite/Laravel-Excel
        return redirect()->back()->with('info', 'Fitur export Excel akan segera tersedia');
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
    
    /**
     * Dashboard Statistik (API untuk chart)
     */
    public function getStats(Request $request)
    {
        $year = $request->get('year', date('Y'));
        
        // Statistik peminjaman per bulan
        $monthlyStats = Peminjaman::select(
            DB::raw('MONTH(tanggal_peminjaman) as month'),
            DB::raw('COUNT(*) as total')
        )
        ->whereYear('tanggal_peminjaman', $year)
        ->groupBy(DB::raw('MONTH(tanggal_peminjaman)'))
        ->orderBy('month')
        ->get()
        ->pluck('total', 'month')
        ->toArray();
        
        // Isi bulan yang kosong
        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyData[] = $monthlyStats[$i] ?? 0;
        }
        
        // Statistik per kategori barang
        $categoryStats = Barang::select(
            'kategori',
            DB::raw('COUNT(*) as total')
        )
        ->groupBy('kategori')
        ->get()
        ->pluck('total', 'kategori')
        ->toArray();
        
        // Top 5 barang paling sering dipinjam
        $topBarang = Barang::withCount(['peminjaman as peminjaman_count'])
            ->orderBy('peminjaman_count', 'desc')
            ->limit(5)
            ->get();
        
        // Top 5 mahasiswa paling aktif
        $topMahasiswa = User::where('role', 'mahasiswa')
            ->withCount(['peminjaman as peminjaman_count'])
            ->orderBy('peminjaman_count', 'desc')
            ->limit(5)
            ->get();
        
        return response()->json([
            'monthly' => $monthlyData,
            'categories' => $categoryStats,
            'top_barang' => $topBarang,
            'top_mahasiswa' => $topMahasiswa,
            'year' => $year,
        ]);
    }


    /**
 * Edit data dari laporan
 */
public function editFromLaporan(Request $request, $type, $id)
{
    switch ($type) {
        case 'peminjaman':
            $peminjaman = Peminjaman::findOrFail($id);
            $barang = Barang::all();
            $mahasiswa = User::where('role', 'mahasiswa')->get();
            
            return view('laporan.edit-peminjaman', compact('peminjaman', 'barang', 'mahasiswa'));
            
        case 'mahasiswa':
            $mahasiswa = User::where('role', 'mahasiswa')->findOrFail($id);
            
            $stats = [
                'total_peminjaman' => $mahasiswa->peminjaman()->count(),
                'peminjaman_aktif' => $mahasiswa->peminjaman()->where('status', 'dipinjam')->count(),
                'dikembalikan' => $mahasiswa->peminjaman()->where('status', 'dikembalikan')->count(),
                'terlambat' => $mahasiswa->peminjaman()->where('status', 'terlambat')->count(),
            ];
            
            return view('laporan.edit-mahasiswa', compact('mahasiswa', 'stats'));
            
        case 'barang':
            $barang = Barang::findOrFail($id);
            
            $stats = [
                'total_peminjaman' => $barang->peminjaman()->count(),
                'sedang_dipinjam' => $barang->peminjaman()->where('status', 'dipinjam')->sum('jumlah'),
                'stok_tersedia' => $barang->stok_tersedia,
            ];
            
            return view('laporan.edit-barang', compact('barang', 'stats'));
            
        default:
            return redirect()->back()->with('error', 'Tipe data tidak valid');
    }
}
}