<?php

namespace App\Http\Controllers;

use App\Models\Wiki;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class WikiController extends Controller
{
    public function index()
    {
        if (auth()->user()->isAdmin()) {
            $wikis = Wiki::all();
        } else {
            $wikis = Wiki::getPublishedDocuments();
        }

        return view('documents.list', compact('wikis'));
    }

    public function create()
    {
        return view('documents.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'teaser' => ['required', 'max:255']
        ]);

        if ($validator->fails()) {
            return redirect()->route('wiki.create')->withErrors($validator)->withInput();
        }

        $validated = $validator->validate();
        $validated['body'] = $request->body;
        $validated['slug'] = Str::slug($validated['title']) . '-' . time();

        if ($request->has('show')) {
            $validated['show'] = true;
        }

        Wiki::create($validated);

        return redirect()->route('wiki.index')->with('success', 'Wiki created.');
    }

    public function show(Wiki $wiki)
    {
        if (!$wiki->show && !auth()->user()->isAdmin()) {
            abort(403);
        }

        return view('documents.show', compact('wiki'));
    }

    public function edit(Wiki $wiki)
    {
        return view('documents.edit', compact('wiki'));
    }

    public function update(Wiki $wiki, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'teaser' => ['required', 'max:255']
        ]);

        if ($validator->fails()) {
            return redirect()->route('wiki.create')->withErrors($validator)->withInput();
        }

        $validated = $validator->validate();
        $validated['body'] = $request->body;
        $validated['slug'] = Str::slug($validated['title']) . '-' . time();

        if ($request->has('show')) {
            $validated['show'] = true;
        }

        $wiki->update($validated);

        return redirect()->route('wiki.index')->with('success', 'Wiki updated.');
    }

    public function destroy(Wiki $wiki)
    {
        $wiki->delete();

        return redirect()->route('wiki.index')->with('success', 'Wiki deleted.');
    }
}
