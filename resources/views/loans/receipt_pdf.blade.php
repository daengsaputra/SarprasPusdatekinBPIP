<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <style>
    body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; }
    .center { text-align: center; }
    h1 { font-size: 18px; margin: 8px 0 0; }
    h2 { font-size: 16px; margin: 2px 0 14px; }
    table.meta td { padding: 3px 6px; vertical-align: top; }
    table.items { width:100%; border-collapse: collapse; margin-top: 14px; }
    table.items th, table.items td { border: 1px solid #ccc; padding: 6px 8px; }
    .mt-2 { margin-top: 12px; }
    .mt-4 { margin-top: 28px; }
    .sign { width: 45%; display: inline-block; }
  </style>
</head>
<body>
  <div class="center">
    <div style="font-size:11px">SARPRAS PUSDATEKIN</div>
    <img src="{{ public_path('images/logo-sarpras.svg') }}" alt="Logo" style="height:72px; margin:6px 0">
    <h1>BUKTI PEMINJAMAN BARANG</h1>
    <h2>SARANA PRASARANA ASET PUSDATeKIN</h2>
  </div>

  <table class="meta">
    <tr><td style="width:120px">Kode Pinjam</td><td>: {{ $batch }}</td></tr>
    <tr><td>Nama Peminjam</td><td>: {{ $borrower }}</td></tr>
    @if($contact)
      <tr><td>Kontak</td><td>: {{ $contact }}</td></tr>
    @endif
    <tr><td>Unit Kerja</td><td>: {{ $unit }}</td></tr>
    <tr><td>Tanggal Pinjam</td><td>: {{ \Illuminate\Support\Carbon::parse($loan_date)->format('Y-m-d') }}</td></tr>
    <tr><td>Estimasi Kembali</td><td>: {{ $return_plan?\Illuminate\Support\Carbon::parse($return_plan)->format('Y-m-d'):'-' }}</td></tr>
    <tr><td>Petugas</td><td>: {{ $officer }}</td></tr>
  </table>

  <table class="items">
    <thead>
      <tr>
        <th style="width:40px">No</th>
        <th style="width:120px">Kode Barang</th>
        <th>Nama Barang</th>
        <th style="width:70px">Jumlah</th>
      </tr>
    </thead>
    <tbody>
      @foreach($items as $i=>$row)
        <tr>
          <td class="center">{{ $i+1 }}</td>
          <td>{{ $row->asset->code }}</td>
          <td>{{ $row->asset->name }}</td>
          <td class="center">{{ $row->quantity }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <div class="mt-4">
    <div class="sign">Peminjam<br><br><br><strong>{{ $borrower }}</strong></div>
    <div class="sign" style="float:right; text-align:right">Petugas<br><br><br><strong>{{ $officer }}</strong></div>
  </div>

  <div class="mt-2" style="font-size:10px; color:#666">Dicetak: {{ $printed_at->format('Y-m-d H:i') }}</div>
</body>
</html>

