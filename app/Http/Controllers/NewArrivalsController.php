<?php

namespace App\Http\Controllers;

use App\Models\newArrivals;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewArrivalsController extends Controller
{
    public function index()
    {
        $newArrivals = newArrivals::latest()->paginate(10);
        return view('admin.newArrivals.index', compact('newArrivals'));
    }

    public function create()
    {
        return view('admin.newArrivals.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('newArrivals', 'public');
            $validated['image'] = $imagePath;
        }

        newArrivals::create($validated);

        return redirect()->route('admin.newArrivals.index')
            ->with('success', 'New arrival berhasil ditambahkan');
    }

    public function edit(newArrivals $newArrival)
    {
        return view('admin.newArrivals.edit', compact('newArrival'));
    }

    public function update(Request $request, newArrivals $newArrival)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($newArrival->image) {
                Storage::disk('public')->delete($newArrival->image);
            }
            $imagePath = $request->file('image')->store('newArrivals', 'public');
            $validated['image'] = $imagePath;
        }

        $newArrival->update($validated);

        return redirect()->route('admin.newArrivals.index')
            ->with('success', 'New arrival berhasil diperbarui');
    }

    public function destroy(newArrivals $newArrival)
    {
        if ($newArrival->image) {
            Storage::disk('public')->delete($newArrival->image);
        }
        $newArrival->delete();

        return redirect()->route('admin.newArrivals.index')
            ->with('success', 'New arrival berhasil dihapus');
    }
}