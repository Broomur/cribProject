<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Album;
use App\Rules\Maxsize;
use App\Models\Picture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Albums/Index', [
            'albums' => Album::where('exists', true)
                ->where('name', '!=', 'public-presentation')
                ->with('pictures')
                ->groupBy('id')
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
        return Inertia::render('Albums/Create', [
            //
        ]);
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

        $validated = $request->validate([
            'album' => 'required|string|max:55',
            'pictures' => ['array', 'required', 'max:20', new Maxsize],
            'pictures.*' => 'image|required',
        ]);

        $user = $request->user();
        
        $control = Album::where(['name' => $validated['album']])->exists();
            if ($control) {
                $album = Album::where(['name' => $validated['album']])->first();
                $album->update(['exists' => true,]);
            } else {
                $album = Album::create([
                    'name' => $validated['album'],
                    'user_id' => $user->id,
                    'exists' => true,    
                ]);
            }
            
        foreach ($validated['pictures'] as $part) {
            $name = $part->hashName();
            $store = Storage::disk('pictures')->put('/', $part);
            $path = Storage::disk('pictures')->url($name);
            $picture = Picture::create([
                'path' => $path,
                'user_id' => $user->id,
                'album_id' => $album->id,
            ]);
        }

        if ($route === "admin.index") {
            return Redirect::route('admin.index')->with('message', 'Les photos ont été importées avec succès !');
        }

        return Redirect::route('albums.index')->with('message', 'Les photos ont été importées avec succès !');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function show(Album $album)
    {   
        return Inertia::render('Albums/Show', [
            'album' => Album::where('id', $album->id)
                ->with('pictures')
                ->simplePaginate(10),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function edit(Album $album)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Album $album)
    {
        $validated = $request->validate([
            'album' => 'required|string|max:55',
        ]);

        $updateAlbum = Album::where('id', $album->id)->update(['name' => $validated['album']]);

        return Redirect::route('albums.index')->with('message', 'Le nom de l\'album a été mis à jour');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function destroy(Album $album)
    {
        $route = app('router')->getRoutes(url()->previous())->match(app('request')->create(url()->previous()))->getName();

        $deletePictures = Picture::where('album_id', $album->id)
            ->get();

        foreach($deletePictures as $deletePicture) {
            $name = explode('/', $deletePicture->path)[5];
            Storage::disk('pictures')->delete($name);
        }

        $deleteDB = Picture::where('album_id', $album->id)
            ->delete();

        $shutdownAlbum = $album->update([
            'exists' => false,
        ]);

        if ($route === "admin.index") {
            return Redirect::route('admin.index')->with('message', 'Les photos ont été importées avec succès !');
        }

        return Redirect::route('albums.index')->with('message', 'L\'album a été supprimé.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Album $album
     * @param  \App\Models\Picture  $picture
     * @return \Illuminate\Http\Response
     */
    public function pictureDestroy(Album $album, Picture $picture)
    {
        $route = app('router')->getRoutes(url()->previous())->match(app('request')->create(url()->previous()))->getName();

        $deleteDB = Picture::where('id', $picture->id)->delete();

        $name = explode('/', $picture->path)[5];
        $deleteFile = Storage::disk('pictures')->delete($name);

        if ($route === "admin.edit") {
            return Redirect::route('admin.edit', [1])->with('message', 'La photo a été supprimée.');
        }

        return Redirect::route('albums.show', [$album])->with('message', 'La photo a été supprimée.');
    }
}
