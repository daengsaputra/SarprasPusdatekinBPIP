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
  <title>Laporan Peminjaman</title>
  </head>
<body>
  <h1>Laporan Peminjaman</h1>
  <div class="muted">Periode: {{ $summary['start'] }} s/d {{ $summary['end'] }} ({{ $summary['periode'] }})</div>
  <div class="muted">Total Transaksi: {{ $summary['total_transaksi'] }} | Total Jumlah: {{ $summary['total_jumlah'] }}</div>
  <br>
  <table>
    <thead>
      <tr>
        <th style="width: 18%">Tanggal Pinjam</th>
        <th>Aset</th>
        <th style="width: 28%">Peminjam</th>
        <th style="width: 10%" class="right">Jumlah</th>
        <th style="width: 12%">Status</th>
      </tr>
    </thead>
    <tbody>
      @foreach($rows as $row)
        <tr>
          <td>{{ \Illuminate\Support\Carbon::parse($row->loan_date)->format('Y-m-d') }}</td>
          <td>{{ $row->asset->code }} — {{ $row->asset->name }}</td>
          <td>{{ $row->borrower_name }}</td>
          <td class="right">{{ $row->quantity }}</td>
          <td>{{ $row->status }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>

