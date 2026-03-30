<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CollectionController extends Controller
{
   public function index()
    {
        $collections = Collection::latest()->paginate(10);
        return view('admin.collections.index', compact('collections'));
    }

    public function create()
    {
        return view('admin.collections.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:collections',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('icon')) {
            $iconPath = $request->file('icon')->store('collections', 'public');
            $validated['icon'] = $iconPath;
        }

        Collection::create($validated);

        return redirect()->route('admin.collections.index')
            ->with('success', 'Koleksi berhasil ditambahkan');
    }

    public function edit(Collection $collection)
    {
        return view('admin.collections.edit', compact('collection'));
    }

    public function update(Request $request, Collection $collection)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:collections,name,' . $collection->id,
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('icon')) {
            if ($collection->icon) {
                Storage::disk('public')->delete($collection->icon);
            }
            $iconPath = $request->file('icon')->store('collections', 'public');
            $validated['icon'] = $iconPath;
        }

        $collection->update($validated);

        return redirect()->route('admin.collections.index')
            ->with('success', 'Koleksi berhasil diperbarui');
    }

    public function destroy(Collection $collection)
    {
        if ($collection->icon) {
            Storage::disk('public')->delete($collection->icon);
        }
        $collection->delete();

        return redirect()->route('admin.collections.index')
            ->with('success', 'Koleksi berhasil dihapus');
    }
}
