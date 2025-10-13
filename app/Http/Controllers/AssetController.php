<?php

namespace App\Http\Controllers;

use App\Exports\AssetsExport;
use App\Exports\AssetsTemplateExport;
use App\Imports\AssetsImport;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->renderList($request, [
            'context' => 'inventory',
            'kind' => Asset::KIND_INVENTORY,
        ]);
    }

    /**
     * Display the loanable asset list.
     */
    public function loanable(Request $request)
    {
        return $this->renderList($request, [
            'context' => 'loanable',
            'kind' => Asset::KIND_LOANABLE,
        ]);
    }

    /**
     * Shared list renderer for both contexts.
     */
    protected function renderList(Request $request, array $options = [])
    {
        $q = $request->input('q');
        $status = $request->input('status');
        $category = $request->input('category');
        $availableOnly = $request->input('available') === '1';
        $sort = $request->input('sort');
        $dir = $request->input('dir', 'asc') === 'desc' ? 'desc' : 'asc';
        $kind = $options['kind'] ?? $request->input('kind');

        if (array_key_exists('kind', $options)) {
            $kind = $options['kind'];
            $request->merge(['kind' => $kind]);
        }

        $query = Asset::query();

        if ($q) {
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%$q%")
                  ->orWhere('code', 'like', "%$q%")
                  ->orWhere('description', 'like', "%$q%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($category) {
            $query->where('category', $category);
        }

        if ($availableOnly) {
            $query->where('quantity_available', '>', 0);
        }

        if ($kind) {
            $query->where('kind', $kind);
        }

        $allowed = [
            'code' => 'code',
            'name' => 'name',
            'category' => 'category',
            'status' => 'status',
            'qty_total' => 'quantity_total',
            'qty_available' => 'quantity_available',
        ];

        if ($sort && isset($allowed[$sort])) {
            $query->orderBy($allowed[$sort], $dir);
        } else {
            $query->orderBy('name');
        }

        $assets = $query->paginate(10)->withQueryString();

        $append = [];
        if ($availableOnly) {
            $append['available'] = '1';
        }
        if ($status) {
            $append['status'] = $status;
        }
        if ($kind) {
            $append['kind'] = $kind;
        }
        if ($append) {
            $assets->appends($append);
        }

        $categoriesQuery = Asset::whereNotNull('category');
        if ($kind) {
            $categoriesQuery->where('kind', $kind);
        }
        $categories = $categoriesQuery->distinct()->orderBy('category')->pluck('category');

        return view($options['view'] ?? 'assets.index', [
            'assets' => $assets,
            'categories' => $categories,
            'q' => $q,
            'status' => $status,
            'category' => $category,
            'availableOnly' => $availableOnly,
            'sort' => $sort,
            'dir' => $dir,
            'context' => $options['context'] ?? 'inventory',
            'kind' => $kind,
        ]);
    }

    protected function routeNameForKind(?string $kind): string
    {
        return $kind === Asset::KIND_LOANABLE ? 'assets.loanable' : 'assets.index';
    }

    /**
     * Show the form for creating the specified resource.
     */
    public function create()
    {
        return view('assets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:assets,code',
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'kind' => 'required|in:' . implode(',', [Asset::KIND_LOANABLE, Asset::KIND_INVENTORY]),
            'quantity_total' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
            'photo' => 'nullable|image|mimes:' . implode(',', config('bpip.asset_photo_mimes', config('bpip.user_photo_mimes'))) . '|max:' . (int) config('bpip.asset_photo_max_kb', config('bpip.user_photo_max_kb')),
        ]);

        $validated['quantity_available'] = $validated['quantity_total'];

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('assets', 'public');
        }

        Asset::create($validated);

        return redirect()->route($this->routeNameForKind($validated['kind']))
            ->with('success', 'Aset berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Asset $asset)
    {
        return redirect()->route('assets.edit', $asset);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asset $asset)
    {
        return view('assets.edit', compact('asset'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:assets,code,' . $asset->id,
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'kind' => 'required|in:' . implode(',', [Asset::KIND_LOANABLE, Asset::KIND_INVENTORY]),
            'quantity_total' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
            'photo' => 'nullable|image|mimes:' . implode(',', config('bpip.asset_photo_mimes', config('bpip.user_photo_mimes'))) . '|max:' . (int) config('bpip.asset_photo_max_kb', config('bpip.user_photo_max_kb')),
        ]);

        $borrowed = max(0, $asset->quantity_total - $asset->quantity_available);
        $validated['quantity_available'] = max(0, $validated['quantity_total'] - $borrowed);

        if ($request->hasFile('photo')) {
            if ($asset->photo) {
                Storage::disk('public')->delete($asset->photo);
            }
            $validated['photo'] = $request->file('photo')->store('assets', 'public');
        }

        $asset->update($validated);

        return redirect()->route($this->routeNameForKind($validated['kind']))
            ->with('success', 'Aset berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asset $asset)
    {
        if ($asset->loans()->where('status', 'borrowed')->exists()) {
            return redirect()->back()->with('error', 'Tidak bisa menghapus aset yang masih dipinjam.');
        }

        if ($asset->photo) {
            Storage::disk('public')->delete($asset->photo);
        }

        $kind = $asset->kind;
        $asset->delete();

        return redirect()->route($this->routeNameForKind($kind))
            ->with('success', 'Aset berhasil dihapus.');
    }

    public function exportExcel(Request $request)
    {
        $filters = $request->only('q', 'status', 'category', 'kind');

        return Excel::download(new AssetsExport($filters), 'assets.xlsx');
    }

    public function importForm(Request $request)
    {
        $kind = $request->input('kind');

        return view('assets.import', compact('kind'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv,xls',
        ]);

        $kind = $request->input('kind');
        $defaultKind = in_array($kind, [Asset::KIND_LOANABLE, Asset::KIND_INVENTORY], true) ? $kind : null;

        $import = new AssetsImport($defaultKind);
        Excel::import($import, $request->file('file'));

        $stats = $import->stats;

        return redirect()->route($this->routeNameForKind($defaultKind))->with(
            'success',
            "Import selesai. Ditambah: {$stats['inserted']}, Diupdate: {$stats['updated']}, Dilewati: {$stats['skipped']}"
        );
    }

    public function exportTemplate()
    {
        return Excel::download(new AssetsTemplateExport(), 'template_aset.xlsx');
    }

    public function destroyPhoto(Asset $asset)
    {
        if ($asset->photo) {
            Storage::disk('public')->delete($asset->photo);
            $asset->photo = null;
            $asset->save();
        }

        return back()->with('success', 'Foto aset telah dihapus.');
    }
}