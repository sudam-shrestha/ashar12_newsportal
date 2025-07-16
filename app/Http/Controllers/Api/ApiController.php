<?php

namespace App\Http\Controllers\Api;

use Anuzpandey\LaravelNepaliDate\LaravelNepaliDate;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CompanyResource;
use App\Models\Article;
use App\Models\Category;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function company()
    {
        $company = Company::first();
        return new CompanyResource($company);
    }

    public function date()
    {
        $date = LaravelNepaliDate::from(now())->toNepaliDate(format: 'D, j F Y', locale: 'np');
        return $date;
    }

    public function latest_articles()
    {
        $latest_articles = Article::orderBy('id', 'desc')->limit(5)->get();
        return ArticleResource::collection($latest_articles);
    }

    public function trending_articles()
    {
        $trending_articles = Article::orderBy('views', 'desc')->limit(5)->get();
        return ArticleResource::collection($trending_articles);
    }

    public function categories()
    {
        $categories = Category::all();
        return CategoryResource::collection($categories);
    }

    public function category(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "title" => "required|unique:categories,title",
            "slug" => "required|unique:categories,slug",
        ]);

        if ($validator->fails()) {
            return  response()->json([
                "success" => false,
                "message" => $validator->errors()
            ]);
        }

        $category = new Category();
        $category->title = $request->title;
        $category->slug = $request->slug;
        $category->meta_keywords = $request->keywords;
        $category->meta_description = $request->description;
        $category->save();

        return response()->json([
            "success" => true,
            "message" => "Category Created Successfully"
        ]);
    }
}
