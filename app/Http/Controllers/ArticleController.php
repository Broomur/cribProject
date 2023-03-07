<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Articles/Index', [
            'articles' => Article::where('exists', true)
                                ->with('users')
                                ->with('categories')
                                ->orderBy('created_at', 'desc')
                                ->simplePaginate(6),
            'categoriesNurse' => Category::whereIn('name', ['activités', 'infirmerie', 'commission recrutement', 'administration'])
                                ->get(),
            'categoriesBoss' => Category::whereIn('name', ['commission recrutement', 'activités', 'administration'])
                                ->get(),
            'categories' => Category::get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Articles/Create', [
            'categoriesNurse' => Category::whereIn('name', ['activités', 'infirmerie', 'commission recrutement', 'administration'])
                                ->get(),
            'categoriesBoss' => Category::whereIn('name', ['commission recrutement', 'activités', 'administration'])
                                ->get(),
            'categories' => Category::get(),
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
            'name' => 'required|string|max:55',
            'content' => 'required|string|max:1500',
            'category' => 'nullable|exists:categories,name',
        ]);
        
        $user = $request->user();

        $userRole = $user->roles()->select('id')->get();

        if ($validated['category'] === null) {
            $category = Category::where('role_id', $userRole[0]->id)->first();
        } else {
            $category = Category::where('name', $validated['category'])->first();
        }

        $article = Article::create([
            'name' => $validated['name'],
            'content' => $validated['content'],
            'user_id' => $user->id,
            'category_id' => $category->id,
            'exists' => true,
        ]);

        return Redirect::route('articles.index')->with('message', 'Votre article a été publié avec succès.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        return Inertia::render('Articles/Show', [
            'article' => Article::where('id', $article->id)->with('users')->with('categories')->get(),
            'categories' => Category::get(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'name' => 'string|max:55',
            'content' => 'string|max:1500',
            'category' => 'nullable|exists:categories,name',
        ]);

        if ($validated['category'] !== null) {
            $category = Category::where('name', $validated['category'])->first();
            $category = $category->id;
        } else {
            $category = $article->category_id;
        }

        $updateArticle = $article->update([
            'name' => $validated['name'],
            'content' => $validated['content'],
            'category_id' => $category,
        ]);
        
        return Redirect::route('articles.index')->with('message', 'L\'article a été modifié.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        $deleteArticle = $article->update([
            'exists' => false,
        ]);

        return Redirect::route('articles.index')->with('message', 'Article supprimé.');
    }
}
