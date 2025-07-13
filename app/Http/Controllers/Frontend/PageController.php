<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class PageController extends Controller
{

    public function __construct()
    {
        $company = Company::first();
        $categories = Category::all();
        View::share([
            "company" => $company,
            "categories" => $categories
        ]);
    }


    public function home()
    {
        $latest_articles = Article::orderBy('id', 'desc')->limit(5)->get();
        $trending_articles = Article::orderBy('views', 'desc')->limit(5)->get();
        return view('frontend.home', compact('latest_articles', 'trending_articles'));
    }

    public function category()
    {
        return view('frontend.category');
    }
}
