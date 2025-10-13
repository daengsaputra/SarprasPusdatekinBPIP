<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $q = request('q');
        $status = request('status'); // borrowed|returned|all
        $unit = request('unit');
        $from = request('from');
        $to = request('to');

        $query = Loan::with('asset');
        $sort = request('sort');
        $dir = request('dir','desc') === 'asc' ? 'asc' : 'desc';
        if ($q) {
            $query->where(function($w) use ($q){
                $w->whereHas('asset', function($a) use ($q){
                        $a->where('name','like',"%$q%")
                          ->orWhere('code','like',"%$q%");
                    })
                  ->orWhere('borrower_name','like',"%$q%");
            });
        }
        if ($status && in_array($status, ['borrowed','returned'])) {
            $query->where('status',$status);
        }
        if ($unit) {
            $query->where('unit',$unit);
        }
        if ($from) {
            $query->whereDate('loan_date','>=',$from);
        }
        if ($to) {
            $query->whereDate('loan_date','<=',$to);
        }

        // Sorting
        $allowed = [
            'loan_date' => 'loans.loan_date',
            'return_date_planned' => 'loans.return_date_planned',
            'status' => 'loans.status',
            'quantity' => 'loans.quantity',
            'borrower_name' => 'loans.borrower_name',
            'asset' => 'a.name',
            'unit' => 'loans.unit',
        ];
        if ($sort === 'asset') {
            $query->leftJoin('assets as a','a.id','=','loans.asset_id')->select('loans.*');
        }
        if ($sort && isset($allowed[$sort])) {
            $query->orderBy($allowed[$sort], $dir);
        } else {
            $query->orderByDesc('loans.loan_date');
        }

        $loans = $query->paginate(10)->withQueryString();
        $units = config('bpip.units');
        return view('loans.index', compact('loans','units','q','status','unit','from','to','sort','dir'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $assets = Asset::where('status', 'active')
            ->where('kind', Asset::KIND_LOANABLE)
            ->where('quantity_available', '>', 0)
            ->orderBy('name')
            ->get();
        $units = config('bpip.units');
        return view('loans.create', compact('assets','units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'borrower_name' => 'required|string|max:255',
            'borrower_contact' => 'nullable|string|max:255',
            'unit' => ['required','string', \Illuminate\Validation\Rule::in(config('bpip.units'))],
            'quantity' => 'required|integer|min:1',
            'loan_date' => 'required|date',
            'return_date_planned' => 'nullable|date|after_or_equal:loan_date',
            'notes' => 'nullable|string',
        ]);
        // If items came as JSON string from the cart UI, decode it
        if (is_string($request->input('items'))) {
            $decoded = json_decode($request->input('items'), true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $data['items'] = array_values(array_map(function($it){
                    return [
                        'asset_id' => (int)($it['asset_id'] ?? 0),
                        'quantity' => max(1, (int)($it['quantity'] ?? 0)),
                    ];
                }, $decoded));
            }
        }

        $asset = Asset::findOrFail($validated['asset_id']);
        if ($asset->kind !== Asset::KIND_LOANABLE) {
            return back()->withInput()->withErrors(['asset_id' => 'Barang yang dipilih bukan data peminjaman.']);
        }
        if ($validated['quantity'] > $asset->quantity_available) {
            return back()->withInput()->withErrors(['quantity' => 'Jumlah melebihi stok tersedia.']);
        }

        $loan = Loan::create($validated + ['status' => 'borrowed']);

        $asset->decrement('quantity_available', $validated['quantity']);

        return redirect()->route('loans.index')->with('success', 'Peminjaman berhasil dicatat.');
    }

    // Store multiple items in one submission (cart-style)
    public function storeBatch(Request $request)
    {
        $data = $request->validate([
            'borrower_name' => 'required|string|max:255',
            'borrower_contact' => 'nullable|string|max:255',
            'unit' => ['required','string', \Illuminate\Validation\Rule::in(config('bpip.units'))],
            'loan_date' => 'required|date',
            'return_date_planned' => 'nullable|date|after_or_equal:loan_date',
            'notes' => 'nullable|string',
        ]);

        // Ambil items dari hidden field JSON atau array biasa
        $itemsRaw = $request->input('items');
        if (is_string($itemsRaw)) {
            $decoded = json_decode($itemsRaw, true);
            $items = json_last_error() === JSON_ERROR_NONE ? $decoded : [];
        } else {
            $items = is_array($itemsRaw) ? $itemsRaw : [];
        }
        if (!is_array($items) || count($items) === 0) {
            return back()->withInput()->withErrors(['items' => 'Pilih minimal satu item.']);
        }

        // Sanitasi
        $items = array_values(array_map(function($it){
            return [
                'asset_id' => (int)($it['asset_id'] ?? 0),
                'quantity' => max(1, (int)($it['quantity'] ?? 0)),
            ];
        }, $items));

        $batch = 'PJ'.now()->format('YmdHis');
        DB::transaction(function () use ($data, $items, $batch) {
            foreach ($items as $item) {
                $asset = Asset::lockForUpdate()->findOrFail($item['asset_id']);
                if ($asset->kind !== Asset::KIND_LOANABLE) {
                    abort(422, 'Aset ' . $asset->name . ' bukan barang peminjaman.');
                }
                if ($item['quantity'] > $asset->quantity_available) {
                    abort(422, 'Jumlah melebihi stok tersedia untuk aset '.$asset->name);
                }
                Loan::create([
                    'batch_code' => $batch,
                    'asset_id' => $asset->id,
                    'borrower_name' => $data['borrower_name'],
                    'borrower_contact' => $data['borrower_contact'] ?? null,
                    'unit' => $data['unit'],
                    'quantity' => $item['quantity'],
                    'loan_date' => $data['loan_date'],
                    'return_date_planned' => $data['return_date_planned'] ?? null,
                    'status' => 'borrowed',
                    'notes' => $data['notes'] ?? null,
                ]);
                $asset->decrement('quantity_available', $item['quantity']);
            }
        });

        return redirect()->route('loans.index')
            ->with('success', 'Peminjaman berhasil dicatat. Bukti siap dicetak.')
            ->with('receipt_batch', $batch);
    }

    /**
     * Display the specified resource.
     */
    public function show(Loan $loan)
    {
        return redirect()->route('loans.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Loan $loan)
    {
        return redirect()->route('loans.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Loan $loan)
    {
        return redirect()->route('loans.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Loan $loan)
    {
        if ($loan->status === 'borrowed') {
            return redirect()->route('loans.index')->with('error', 'Tidak bisa menghapus pinjaman yang belum dikembalikan.');
        }
        $loan->delete();
        return redirect()->route('loans.index')->with('success', 'Data peminjaman dihapus.');
    }

    public function returnForm(Loan $loan)
    {
        if ($loan->status === 'returned') {
            return redirect()->route('loans.index')->with('info', 'Pinjaman sudah dikembalikan.');
        }
        return view('loans.return', compact('loan'));
    }

    public function returnUpdate(Request $request, Loan $loan)
    {
        if ($loan->status === 'returned') {
            return redirect()->route('loans.index')->with('info', 'Pinjaman sudah dikembalikan.');
        }

        $validated = $request->validate([
            'return_date_actual' => 'required|date|after_or_equal:' . $loan->loan_date->format('Y-m-d'),
            'notes' => 'nullable|string',
        ]);

        $loan->update([
            'return_date_actual' => $validated['return_date_actual'],
            'notes' => $validated['notes'] ?? $loan->notes,
            'status' => 'returned',
        ]);

        $loan->asset->increment('quantity_available', $loan->quantity);

        return redirect()->route('loans.index')
            ->with('success', 'Pengembalian berhasil diproses.')
            ->with('return_receipt_id', $loan->id);
    }

    public function receipt(Request $request, string $batch)
    {
        $items = Loan::with('asset')->where('batch_code', $batch)->get();
        abort_if($items->isEmpty(), 404);

        $first = $items->first();
        $data = [
            'batch' => $batch,
            'borrower' => $first->borrower_name,
            'contact' => $first->borrower_contact,
            'unit' => $first->unit,
            'loan_date' => $first->loan_date,
            'return_plan' => $first->return_date_planned,
            'officer' => auth()->user()->name ?? 'Petugas',
            'items' => $items,
            'printed_at' => now(),
        ];

        if ($request->boolean('preview')) {
            return view('loans.receipt_preview', $data);
        }

        $pdf = Pdf::loadView('loans.receipt_pdf', $data)->setPaper('a4', 'portrait');

        if ($request->boolean('download')) {
            return $pdf->download('bukti-peminjaman-'.$batch.'.pdf');
        }

        return $pdf->stream('bukti-peminjaman-'.$batch.'.pdf');
    }

            public function returnReceipt(Request $request, Loan $loan)
    {
        abort_if($loan->status !== 'returned', 404);

        $data = [
            'loan' => $loan->load('asset'),
            'printed_at' => now(),
            'officer' => auth()->user()->name ?? 'Petugas',
        ];

        if ($request->boolean('preview')) {
            return view('loans.return_receipt_preview', $data);
        }

        $pdf = Pdf::loadView('loans.return_receipt_pdf', $data)->setPaper('a4', 'portrait');

        if ($request->boolean('download')) {
            return $pdf->download('bukti-pengembalian-'.$loan->id.'.pdf');
        }

        return $pdf->stream('bukti-pengembalian-'.$loan->id.'.pdf');
    }
}

