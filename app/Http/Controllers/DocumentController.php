<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Document/Index', [
            'documents' => Document::where('exists', true)
                ->where('name', '!=', 'public-welcome')
                ->where('name', '!=', 'public-educative')
                ->simplePaginate(6),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Document/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $route = app('router')->getRoutes(url()->previous())->match(app('request')->create(url()->previous()))->getName();

        $name = $request->document->getClientOriginalName();
        
        $control = Document::where('exists', 1)->where('name', $name)->exists();

        if ($control) return Redirect::route('documents.index')->with('error', 'Ce document a déjà été importé.');

        $validated = $request->validate([
            'document' => 'mimes:pdf|required',
        ]);
        
        $user = $request->user();
       
        
        $content = file_get_contents($request->document);

        $path = Storage::disk('documents')->put('/'.$name, $content);
        $path = Storage::disk('documents')->url($name);

        $document = Document::create([
            'path' => $path,
            'name' => $name,
            'user_id' => $user->id,
        ]);

        if ($route === "admin.index") {
            $id = $name === "public-welcome" ? 2 : 3;
            return Redirect::route('admin.index')->with('message', 'Le document a été importé avec succès !');
        }

        return Redirect::route('documents.index')->with('message', 'Le document a été importé avec succès !');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function show(Document $document)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function edit(Document $document)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Document $document)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function destroy(Document $document)
    {
        $route = app('router')->getRoutes(url()->previous())->match(app('request')->create(url()->previous()))->getName();

        $deleteFile = Storage::disk('documents')->delete($document->name);

        $deleteDB = $document->update([
            'exists' => false,
        ]);

        if ($route === "admin.index") {
            $id = $name === "public-welcome" ? 2 : 3;
            return Redirect::route('admin.index')->with('message', 'Le document a été supprimé avec succès !');
        }


        return Redirect::route('documents.index')->with('message', 'Le document a été supprimé.');
    }
}
