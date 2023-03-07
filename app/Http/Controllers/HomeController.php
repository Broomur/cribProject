<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Album;
use App\Models\Article;
use App\Models\Document;
use App\Models\HomeText;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class HomeController extends Controller
{
    public function index()
    {
        return Inertia::render('Welcome', [
            'intro' => HomeText::where('type', 'intro')->first(),
            'welcome' => HomeText::where('type', 'welcome')->first(),
            'educative' => HomeText::where('type', 'educative')->first(),
            'jobOffers' => Article::where('category_id', 3)->where('exists', true)->get(),
            'introAlbum' => Album::where('name', 'public-presentation')
                ->with('pictures')
                ->first(),
            'educativeDoc' => Document::where('name', 'public-educative')
                ->first(),
            'welcomeDoc' => Document::where('name', 'public-welcome')
                ->first(),
        ]);
    }
}
