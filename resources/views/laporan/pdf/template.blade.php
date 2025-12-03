<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { margin: 0; color: #333; }
        .header p { margin: 5px 0; color: #666; }
        .info { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background-color: #f8f9fa; text-align: left; padding: 8px; border: 1px solid #dee2e6; }
        td { padding: 8px; border: 1px solid #dee2e6; }
        .footer { margin-top: 30px; text-align: center; color: #666; font-size: 11px; }
        .badge { padding: 3px 8px; border-radius: 10px; font-size: 11px; }
        .badge-warning { background-color: #ffc107; color: #000; }
        .badge-success { background-color: #198754; color: #fff; }
        .badge-danger { background-color: #dc3545; color: #fff; }
    </style>
</head>
<body>
    <div class="header">
        <h1>SISTEM PEMINJAMAN BARANG LAB RSI</h1>
        <h2>{{ $title }}</h2>
        <p>Periode: {{ $start_date }} s/d {{ $end_date }}</p>
    </div>
    
    <div class="info">
        <p>Tanggal Cetak: {{ date('d/m/Y H:i:s') }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Peminjaman</th>
                <th>Barang</th>
                <th>Mahasiswa</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->kode_peminjaman }}</td>
                <td>{{ $item->barang->nama }}</td>
                <td>{{ $item->mahasiswa->name }}</td>
                <td>{{ $item->tanggal_pinjam->format('d/m/Y') }}</td>
                <td>{{ $item->tanggal_kembali->format('d/m/Y') }}</td>
                <td>
                    @if($item->status == 'dipinjam')
                        <span class="badge badge-warning">Dipinjam</span>
                    @elseif($item->status == 'dikembalikan')
                        <span class="badge badge-success">Dikembalikan</span>
                    @else
                        <span class="badge badge-danger">Terlambat</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p>Dicetak oleh: {{ Auth::user()->name }}</p>
        <p>&copy; {{ date('Y') }} - Sistem Peminjaman Barang Lab RSI</p>
    </div>
</body>
</html>