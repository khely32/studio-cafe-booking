<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Folder;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $query = Page::query();

        $sort = $request->get('sort', 'title');
        $folderFilter = $request->get('folder');

        if ($folderFilter) {
            $query->where('folder_id', $folderFilter);
        }

        if ($sort === 'title') {
            $query->orderBy('title');
        } elseif ($sort === 'newest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('sort_order')->orderBy('title');
        }

        $pages = $query->paginate(10)->withQueryString();
        $folders = Folder::withCount('pages')->get();
        $slackUrl = Setting::get('slack_webhook_url');

        return view('admin.pages.index', compact('pages', 'folders', 'slackUrl'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'meta_description' => 'nullable|string|max:500',
            'is_published' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_published'] = $request->boolean('is_published');

        Page::create($validated);

        return redirect()->route('admin.pages.index')->with('success', 'Page created successfully.');
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'meta_description' => 'nullable|string|max:500',
            'is_published' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_published'] = $request->boolean('is_published');

        $page->update($validated);

        return redirect()->route('admin.pages.index')->with('success', 'Page updated successfully.');
    }

    public function destroy(Page $page)
    {
        $page->delete();
        return redirect()->route('admin.pages.index')->with('success', 'Page deleted.');
    }

    public function duplicate(Page $page)
    {
        $newPage = $page->replicate();
        $newPage->title = $page->title . ' (Copy)';
        $newPage->slug = Str::slug($newPage->title);
        $newPage->is_published = false;
        $newPage->save();

        return redirect()->route('admin.pages.edit', $newPage)->with('success', 'Page duplicated. Edit the new copy.');
    }

    public function togglePublish(Page $page)
    {
        $page->update(['is_published' => !$page->is_published]);
        $status = $page->is_published ? 'published' : 'taken offline';
        return redirect()->back()->with('success', "Page {$status}.");
    }

    public function showBySlug($slug)
    {
        $page = Page::where('slug', $slug)->where('is_published', 1)->firstOrFail();
        return view('pages.show', compact('page'));
    }
}
