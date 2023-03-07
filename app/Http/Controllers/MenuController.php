<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Menus/Index', [
            'menus' => Menu::orderBy('id')
                ->orderBy('id', 'asc')
                ->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Menus/Create', [
            'menus' => Menu::orderBy('id')
                ->orderBy('id', 'asc')
                ->get(),
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
        $validated = $request->validate([
            'menus' => 'array',
            'menus.*.PM_menu' => 'nullable|string|max:110',
            'menus.*.BB_menu' => 'nullable|string|max:110',
            'menus.*.snack' => 'nullable|string|max:110',
        ],
        [
            'menus.*.PM_menu.max' => 'Ce champ est limité à 110 caractères.',
            'menus.*.BB_menu.max' => 'Ce champ est limité à 110 caractères.',
            'menus.*.snack.max' => 'Ce champ est limité à 110 caractères.',
        ]);

        $user = $request->user();

        foreach ($request->menus as $key => $menu) {
            Menu::where('id', $key+1)
                ->update(['PM_menu' => $menu['PM_menu'], 'BB_menu' => $menu['BB_menu'], 'snack' => $menu['snack'], 'user_id' => $user->id]);
        }

        return Redirect::route('menus.index')->with('message', 'Menus mis à jours avec succès.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function show(Menu $menu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function edit(Menu $menu)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Menu $menu)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Menu $menu)
    {
        //
    }
}
