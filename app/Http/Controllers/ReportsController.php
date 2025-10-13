<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportsController extends Controller
{
    private function resolveRange(Request $request): array
    {
        $range = $request->query('range', 'week'); // week|month|year|custom
        $start = null; $end = null; $label = '';

        if ($range === 'custom' && $request->filled(['start','end'])) {
            $start = Carbon::parse($request->query('start'))->startOfDay();
            $end   = Carbon::parse($request->query('end'))->endOfDay();
            $label = 'Kustom';
        } elseif ($range === 'month') {
            $start = Carbon::now()->subDays(30)->startOfDay();
            $end   = Carbon::now()->endOfDay();
            $label = '30 Hari Terakhir';
        } elseif ($range === 'year') {
            $start = Carbon::now()->startOfYear();
            $end   = Carbon::now()->endOfDay();
            $label = 'Tahun Ini';
        } else { // default week
            $start = Carbon::now()->subDays(7)->startOfDay();
            $end   = Carbon::now()->endOfDay();
            $label = '7 Hari Terakhir';
            $range = 'week';
        }

        return [$range, $start, $end, $label];
    }

    private function buildReturnsQuery(?string $search, ?string $unit, Carbon $start, Carbon $end)
    {
        $startRange = $start->copy()->startOfDay();
        $endRange = $end->copy()->endOfDay();

        return Loan::query()
            ->with('asset')
            ->where('status', 'returned')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($w) use ($search) {
                    $pattern = '%' . $search . '%';
                    $w->where('borrower_name', 'like', $pattern)
                      ->orWhereHas('asset', function ($a) use ($pattern) {
                        $a->where('name', 'like', $pattern)
                          ->orWhere('code', 'like', $pattern);
                      });
                });
            })
            ->when($unit, fn ($builder) => $builder->where('unit', $unit))
            ->whereBetween('return_date_actual', [$startRange, $endRange]);
    }

    public function loans(Request $request)
    {
        [$range, $start, $end, $label] = $this->resolveRange($request);

        $q = $request->query('q');
        $unit = $request->query('unit');

        $sort = $request->query('sort');
        $dir = $request->query('dir','desc')==='asc'?'asc':'desc';

        $loans = Loan::with('asset')
            ->when($q, function($query) use ($q){
                $query->where(function($w) use ($q){
                    $w->where('borrower_name','like',"%$q%")
                      ->orWhereHas('asset', function($a) use ($q){
                        $a->where('name','like',"%$q%")
                          ->orWhere('code','like',"%$q%");
                      });
                });
            })
            ->when($unit, fn($q2)=>$q2->where('unit',$unit))
            ->whereBetween('loan_date', [$start->toDateString(), $end->toDateString()])
            ->when($sort==='asset', function($q3){ $q3->leftJoin('assets as a','a.id','=','loans.asset_id')->select('loans.*'); })
            ->when(in_array($sort,['loan_date','status','quantity','borrower_name','asset','unit']), function($q4) use($sort,$dir){
                $map = ['loan_date'=>'loans.loan_date','status'=>'loans.status','quantity'=>'loans.quantity','borrower_name'=>'loans.borrower_name','asset'=>'a.name','unit'=>'loans.unit'];
                $q4->orderBy($map[$sort], $dir);
            }, function($q4){ $q4->orderByDesc('loans.loan_date'); })
            ->paginate(15)
            ->withQueryString();

        $summary = [
            'total_transaksi' => (clone $loans)->total(),
            'total_jumlah' => Loan::whereBetween('loan_date', [$start->toDateString(), $end->toDateString()])->sum('quantity'),
            'periode' => $label,
            'start' => $start->toDateString(),
            'end' => $end->toDateString(),
        ];

        $units = config('bpip.units');
        return view('reports.loans', compact('loans','summary','range','start','end','units','q','unit','sort','dir'));
    }

    public function returns(Request $request)
    {
        [$range, $start, $end, $label] = $this->resolveRange($request);

        $q = $request->query('q');
        $unit = $request->query('unit');

        $sort = $request->query('sort');
        $dir = $request->query('dir','desc')==='asc'?'asc':'desc';

        $sortColumns = [
            'return_date_actual' => 'loans.return_date_actual',
            'status' => 'loans.status',
            'quantity' => 'loans.quantity',
            'borrower_name' => 'loans.borrower_name',
            'asset' => 'a.name',
            'unit' => 'loans.unit',
        ];

        $baseReturnsQuery = $this->buildReturnsQuery($q, $unit, $start, $end);

        $tableQuery = clone $baseReturnsQuery;

        if ($sort === 'asset') {
            $tableQuery->leftJoin('assets as a', 'a.id', '=', 'loans.asset_id')->select('loans.*');
        }

        if (array_key_exists($sort, $sortColumns)) {
            $tableQuery->orderBy($sortColumns[$sort], $dir);
        } else {
            $tableQuery->orderByDesc('loans.return_date_actual');
        }

        $returns = $tableQuery->paginate(15)->withQueryString();

        $summary = [
            'total_transaksi' => (clone $baseReturnsQuery)->count(),
            'total_jumlah' => (clone $baseReturnsQuery)->sum('quantity'),
            'periode' => $label,
            'start' => $start->toDateString(),
            'end' => $end->toDateString(),
        ];

        $units = config('bpip.units');
        return view('reports.returns', compact('returns','summary','range','start','end','units','q','unit','sort','dir'));
    }

    public function loansPdf(Request $request)
    {
        [$range, $start, $end, $label] = $this->resolveRange($request);
        $rows = Loan::with('asset')
            ->whereBetween('loan_date', [$start->toDateString(), $end->toDateString()])
            ->orderBy('loan_date')
            ->get();
        $summary = [
            'periode' => $label,
            'start' => $start->toDateString(),
            'end' => $end->toDateString(),
            'total_transaksi' => $rows->count(),
            'total_jumlah' => $rows->sum('quantity'),
        ];
        $pdf = Pdf::loadView('reports.pdf.loans', compact('rows','summary'))
            ->setPaper('a4', 'portrait');
        return $pdf->download('laporan-peminjaman-'.$start->toDateString().'_'.$end->toDateString().'.pdf');
    }

    public function returnsPdf(Request $request)
    {
        [$range, $start, $end, $label] = $this->resolveRange($request);

        $q = $request->query('q');
        $unit = $request->query('unit');

        $rows = $this->buildReturnsQuery($q, $unit, $start, $end)
            ->orderBy('return_date_actual')
            ->get();

        $summary = [
            'periode' => $label,
            'start' => $start->toDateString(),
            'end' => $end->toDateString(),
            'total_transaksi' => $rows->count(),
            'total_jumlah' => $rows->sum('quantity'),
        ];

        $pdf = Pdf::loadView('reports.pdf.returns', compact('rows','summary'))
            ->setPaper('a4', 'portrait');
        return $pdf->download('laporan-pengembalian-'.$start->toDateString().'_'.$end->toDateString().'.pdf');
    }
}


