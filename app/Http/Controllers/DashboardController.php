<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Inertia\Inertia;
use App\Models\Album;
use App\Models\Child;
use App\Models\Article;
use App\Models\Calendar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {  
        return Inertia::render('Dashboard', [
            'calendar' => Calendar::where('exists', true)
                ->where('date', '>', now())
                ->first(),
            'album' => Album::where('exists', true)
                ->where('name', '!=', 'public-presentation')
                ->with('pictures')
                ->orderBy('created_at', 'desc')
                ->first(),
            'menus' => Menu::orderBy('id', 'asc')
                ->get(),                
            'article' => Article::where('exists', true)
                ->with('users')
                ->with('categories')
                ->orderBy('created_at', 'desc')
                ->first(),
            'birthdays' => Child::whereMonth('birthday', now())
                ->orderBy('birthday', 'asc')
                ->get(),
        ]);
    }
}
