<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\MultipleArticlesResponse;
use App\Http\Resources\SingleArticleResponse;
use App\Models\Article;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Put;

#[Prefix('articles')]
class ArticleController extends Controller
{
    #[Get('/')]
    public function list(): MultipleArticlesResponse
    {
        return new MultipleArticlesResponse(null);
    }

    #[Get('/feed', middleware: 'auth')]
    public function feed(): MultipleArticlesResponse
    {
        return new MultipleArticlesResponse(null);
    }

    #[Post('/', middleware: 'auth')]
    public function create(NewArticleRequest $request): SingleArticleResponse
    {
        return new SingleArticleResponse(null);
    }

    #[Put('/{slug}', middleware: 'auth')]
    public function update(Article $article, UpdateArticleRequest $request): SingleArticleResponse
    {
        return new SingleArticleResponse($article);
    }

    #[Delete('/{slug}', middleware: 'auth')]
    public function delete(Article $article)
    {
    }

    #[Post('/{slug}/favorite', middleware: 'auth')]
    public function favorite(Article $article): SingleArticleResponse
    {
        return new SingleArticleResponse($article);
    }

    #[Delete('{slug}/favorite', middleware: 'auth')]
    public function unfavorite(Article $article): SingleArticleResponse
    {
        return new SingleArticleResponse($article);
    }
}
