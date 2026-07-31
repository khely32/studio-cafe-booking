<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use Illuminate\Http\Request;

class AddonController extends Controller
{
    public function index()
    {
        $addons = Addon::orderBy('sort_order')->orderBy('id')->get();
        return view('admin.addons.index', compact('addons'));
    }

    public function create()
    {
        $addon = null;
        return view('admin.addons.form', compact('addon'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        Addon::create($validated);

        return redirect()->route('admin.addons.index')->with('success', 'Add-on created successfully.');
    }

    public function edit(Addon $addon)
    {
        return view('admin.addons.form', compact('addon'));
    }

    public function update(Request $request, Addon $addon)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $addon->update($validated);

        return redirect()->route('admin.addons.index')->with('success', 'Add-on updated successfully.');
    }

    public function destroy(Addon $addon)
    {
        $addon->delete();
        return redirect()->route('admin.addons.index')->with('success', 'Add-on deleted.');
    }
}
