<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <style>
    body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; }
    h1 { font-size: 18px; margin: 0 0 10px; }
    .muted { color: #555; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ccc; padding: 6px 8px; }
    th { background: #f0f3f7; }
    .right { text-align: right; }
  </style>
  <title>Laporan Pengembalian</title>
  </head>
<body>
  <h1>Laporan Pengembalian</h1>
  <div class="muted">Periode: {{ $summary['start'] }} s/d {{ $summary['end'] }} ({{ $summary['periode'] }})</div>
  <div class="muted">Total Transaksi: {{ $summary['total_transaksi'] }} | Total Jumlah: {{ $summary['total_jumlah'] }}</div>
  <br>
  <table>
    <thead>
      <tr>
        <th style="width: 20%">Tanggal Kembali</th>
        <th>Aset</th>
        <th style="width: 30%">Peminjam</th>
        <th style="width: 12%" class="right">Jumlah</th>
      </tr>
    </thead>
    <tbody>
      @foreach($rows as $row)
        <tr>
          <td>{{ \Illuminate\Support\Carbon::parse($row->return_date_actual)->format('Y-m-d') }}</td>
          <td>{{ $row->asset->code }} — {{ $row->asset->name }}</td>
          <td>{{ $row->borrower_name }}</td>
          <td class="right">{{ $row->quantity }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>

