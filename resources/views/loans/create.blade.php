@php($title = 'Pinjam Aset')
@extends('layouts.app')

@push('styles')
<style>
  .list-aset { max-height: 480px; overflow:auto; }
  .status-dot { width:10px;height:10px;border-radius:50%;display:inline-block;margin-right:6px }
  .dot-green{ background:#10b981 }
  .dot-red{ background:#ef4444 }
  .cart-item input[type=number]{ width:90px }
</style>
@endpush

@section('content')
<h1 class="h5 mb-3">Pilih barang yang akan dipinjam</h1>

<div class="row g-3">
  <div class="col-lg-8">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between mb-2">
          <div>
            <label class="me-2">Show</label>
            <select id="pageSize" class="form-select d-inline-block" style="width:80px">
              <option>10</option><option>25</option><option>50</option>
            </select>
            <span class="ms-2">entries</span>
          </div>
          <div>
            <label class="me-2">Search:</label>
            <input type="text" id="search" class="form-control d-inline-block" style="width:220px" placeholder="cari aset...">
          </div>
        </div>

        <div class="table-responsive list-aset">
          <table class="table table-hover align-middle">
            <thead>
              <tr>
                <th>Nama Barang</th>
                <th>ID Barang</th>
                <th>Status</th>
                <th class="text-end">Aksi</th>
              </tr>
            </thead>
            <tbody id="assetTable">
              @foreach($assets as $a)
                <tr data-name="{{ strtolower($a->name.' '.$a->code) }}">
                  <td>{{ $a->name }}</td>
                  <td>{{ $a->code }}</td>
                  <td>
                    @if($a->quantity_available>0)
                      <span class="status-dot dot-green"></span>Tersedia ({{ $a->quantity_available }})
                    @else
                      <span class="status-dot dot-red"></span>Terpinjam
                    @endif
                  </td>
                  <td class="text-end">
                    <button class="btn btn-sm btn-info" onclick="addToCart({ id: {{ $a->id }}, name: @js($a->name), code: @js($a->code), max: {{ $a->quantity_available }} })" {{ $a->quantity_available<=0 ? 'disabled' : '' }}>pilih</button>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <strong>Daftar pinjam</strong>
          <button class="btn btn-sm btn-outline-secondary" onclick="clearCart()">Clear</button>
        </div>
        <div id="cartList" class="mb-3"></div>

        <form id="batchForm" method="POST" action="{{ route('loans.store.batch') }}">
          @csrf
          <input type="hidden" name="items" id="itemsField">

          <div class="mb-2">
            <label class="form-label">Nama Peminjam</label>
            <input type="text" name="borrower_name" class="form-control @error('borrower_name') is-invalid @enderror" required>
            @error('borrower_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-2">
            <label class="form-label">Kontak Peminjam</label>
            <input type="text" name="borrower_contact" class="form-control">
          </div>
          <div class="mb-2">
            <label class="form-label">Unit Kerja BPIP</label>
            <select name="unit" class="form-select @error('unit') is-invalid @enderror" required>
              <option value="">-- pilih unit kerja --</option>
              @foreach(($units ?? config('bpip.units')) as $unit)
                <option value="{{ $unit }}">{{ $unit }}</option>
              @endforeach
            </select>
            @error('unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="row g-2">
            <div class="col-6">
              <label class="form-label">Tanggal Pinjam</label>
              <input type="date" name="loan_date" value="{{ now()->format('Y-m-d') }}" class="form-control" required>
            </div>
            <div class="col-6">
              <label class="form-label">Rencana Kembali</label>
              <input type="date" name="return_date_planned" class="form-control">
            </div>
          </div>
          <div class="mb-2 mt-2">
            <label class="form-label">Catatan</label>
            <textarea name="notes" class="form-control" rows="2"></textarea>
          </div>
          <button class="btn btn-success w-100" type="submit">Simpan</button>
          <a class="btn btn-secondary w-100 mt-2" href="{{ route('loans.index') }}">Batal</a>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
  const cart = [];
  const cartList = document.getElementById('cartList');
  const itemsField = document.getElementById('itemsField');

  function renderCart(){
    if(cart.length===0){ cartList.innerHTML = '<div class="text-muted small">Belum ada item.</div>'; return; }
    cartList.innerHTML = cart.map((it,idx)=>`
      <div class="cart-item d-flex align-items-center justify-content-between border rounded p-2 mb-2">
        <div>
          <div class="fw-semibold">${it.code} — ${it.name}</div>
          <div class="small text-muted">Tersedia: ${it.max}</div>
        </div>
        <div class="d-flex align-items-center gap-2">
          <input type="number" min="1" max="${it.max}" value="${it.qty}" class="form-control form-control-sm" onchange="updateQty(${idx}, this.value)">
          <button class="btn btn-sm btn-outline-danger" onclick="removeItem(${idx})">Hapus</button>
        </div>
      </div>
    `).join('');
    itemsField.value = JSON.stringify(cart.map(x=>({asset_id:x.id, quantity:x.qty})));
  }
  function addToCart(obj){
    const found = cart.find(x=>x.id===obj.id);
    if(found){ found.qty = Math.min(found.qty+1, found.max); }
    else { cart.push({ id: obj.id, name: obj.name, code: obj.code, max: obj.max, qty: 1 }); }
    renderCart();
  }
  function updateQty(idx, val){ cart[idx].qty = Math.max(1, Math.min(parseInt(val||1,10), cart[idx].max)); renderCart(); }
  function removeItem(idx){ cart.splice(idx,1); renderCart(); }
  function clearCart(){ cart.length=0; renderCart(); }
  renderCart();

  // client search filter
  document.getElementById('search').addEventListener('input', (e)=>{
    const val = e.target.value.trim().toLowerCase();
    document.querySelectorAll('#assetTable tr').forEach(tr=>{
      const ok = tr.dataset.name.includes(val);
      tr.style.display = ok? '' : 'none';
    });
  });

  // Submit transform: stringified items to array
  document.getElementById('batchForm').addEventListener('submit', (e)=>{
    if(cart.length===0){ e.preventDefault(); alert('Pilih minimal satu item.'); }
    else {
      // name="items" needs to be JSON string; controller expects array via $request->validate
      // Laravel will parse it as string; we handle in controller by decoding automatically via Request casting not available
      // So keep as string and decode in controller? Simpler: use hidden multiple input fields
    }
  });
</script>
@endpush
