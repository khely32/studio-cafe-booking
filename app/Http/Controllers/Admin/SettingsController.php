<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function homepage()
    {
        $policy = Setting::get('home_policy');
        $guides = Setting::get('home_guides');
        return view('admin.settings.homepage', compact('policy', 'guides'));
    }

    public function updateHomepage(Request $request)
    {
        $validated = $request->validate([
            'home_policy' => 'nullable|string',
            'home_guides' => 'nullable|string',
        ]);

        Setting::set('home_policy', $validated['home_policy'] ?? '');
        Setting::set('home_guides', $validated['home_guides'] ?? '');

        return redirect()->route('admin.settings.homepage')->with('success', 'Homepage policy & guides updated successfully.');
    }
}
