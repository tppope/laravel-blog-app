<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;

class ArticleController extends Controller
{
    public function index()
    {
        return view('posts.index', [
            'posts' => Article::filter(request(['search','category','author']))->get(),
            'currentCategory' => Category::firstWhere('slug', request('category')),
        ]);

    }

    public function show(Article $article)
    {
        return view('posts.show', [
            'post' => $article
        ]);
    }

}
