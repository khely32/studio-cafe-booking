<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function index()
    {
        $templates = Template::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.templates.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.templates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:email,sms,invoice,certificate',
            'subject' => 'nullable|string|max:255',
            'body' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        Template::create($validated);

        return redirect()->route('admin.templates.index')->with('success', 'Template created.');
    }

    public function edit(Template $template)
    {
        return view('admin.templates.edit', compact('template'));
    }

    public function update(Request $request, Template $template)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:email,sms,invoice,certificate',
            'subject' => 'nullable|string|max:255',
            'body' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $template->update($validated);

        return redirect()->route('admin.templates.index')->with('success', 'Template updated.');
    }

    public function destroy(Template $template)
    {
        $template->delete();
        return redirect()->route('admin.templates.index')->with('success', 'Template deleted.');
    }
}
