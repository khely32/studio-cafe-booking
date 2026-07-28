<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Folder;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:7',
        ]);

        Folder::create($validated);

        return redirect()->route('admin.pages.index')->with('success', 'Folder created!');
    }

    public function destroy(Folder $folder)
    {
        $folder->pages()->update(['folder_id' => null]);
        $folder->delete();

        return redirect()->route('admin.pages.index')->with('success', 'Folder deleted.');
    }
}
