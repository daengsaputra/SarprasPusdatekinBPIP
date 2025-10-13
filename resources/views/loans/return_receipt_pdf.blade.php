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
    .mt-4 { margin-top: 28px; }
    .sign { width: 45%; display: inline-block; }
  </style>
</head>
<body>
  <div class="center">
    <div style="font-size:11px">SARPRAS PUSDATEKIN</div>
    <img src="{{ public_path('images/logo-sarpras.svg') }}" alt="Logo" style="height:72px; margin:6px 0">
    <h1>BUKTI PENGEMBALIAN BARANG</h1>
    <h2>SARANA PRASARANA ASET PUSDATeKIN</h2>
  </div>

  <table class="meta">
    <tr><td style="width:140px">ID Peminjaman</td><td>: {{ $loan->id }}</td></tr>
    <tr><td>Nama Peminjam</td><td>: {{ $loan->borrower_name }}</td></tr>
    @if($loan->borrower_contact)
      <tr><td>Kontak</td><td>: {{ $loan->borrower_contact }}</td></tr>
    @endif
    <tr><td>Unit Kerja</td><td>: {{ $loan->unit }}</td></tr>
    <tr><td>Tanggal Pinjam</td><td>: {{ optional($loan->loan_date)->format('Y-m-d') }}</td></tr>
    <tr><td>Tanggal Kembali</td><td>: {{ optional($loan->return_date_actual)->format('Y-m-d') }}</td></tr>
    <tr><td>Petugas</td><td>: {{ $officer }}</td></tr>
  </table>

  <table class="items">
    <thead>
      <tr>
        <th style="width:140px">Kode Barang</th>
        <th>Nama Barang</th>
        <th style="width:70px">Jumlah</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>{{ $loan->asset->code }}</td>
        <td>{{ $loan->asset->name }}</td>
        <td class="center">{{ $loan->quantity }}</td>
      </tr>
    </tbody>
  </table>

  <div class="mt-4">
    <div class="sign">Peminjam<br><br><br><strong>{{ $loan->borrower_name }}</strong></div>
    <div class="sign" style="float:right; text-align:right">Petugas<br><br><br><strong>{{ $officer }}</strong></div>
  </div>

  <div style="font-size:10px; color:#666">Dicetak: {{ $printed_at->format('Y-m-d H:i') }}</div>
</body>
</html>

