<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\MultipleArticlesResource;
use App\Http\Resources\SingleArticleResource;
use App\Models\Article;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Put;
use OpenApi\Attributes as OA;

#[Prefix('articles')]
class ArticleController extends Controller
{
    public const MAX_LIMIT = 20;

    /**
     * Get recent articles globally.
     *
     * Get most recent articles globally. Use query parameters to filter results. Auth is optional
     */
    #[Get('/')]
    #[OA\Get(path: '/articles', operationId: 'GetArticles', tags: ['Articles'])]
    #[OA\Response(
        response: 200,
        description: 'Success',
        content: new OA\JsonContent(ref: MultipleArticlesResource::class)
    )]
    // #[Parameters(factory: ListArticlesParameters::class)]
    public function list(Request $request): MultipleArticlesResource
    {
        $articles = Article::with('author', 'tags', 'favoritedBy')
            ->byAuthor($request->author)
            ->byFavorited($request->favorited)
            ->byTag($request->tag);

        return new MultipleArticlesResource(
            (clone $articles)
                ->orderByDesc('id')
                ->offset($request->offset)
                ->limit(min($request->query('limit', (string) self::MAX_LIMIT), self::MAX_LIMIT))
                ->get(),
            $articles->count()
        );
    }

    /**
     * Get recent articles from users you follow.
     *
     * Get most recent articles from users you follow. Use query parameters to limit. Auth is required
     */
    #[Get('/feed', middleware: 'auth')]
    #[OA\Get(path: '/articles/feed', operationId: 'GetArticlesFeed', tags: ['Articles'], security: ['BearerToken'])]
    #[OA\Response(
        response: 200,
        description: 'Success',
        content: new OA\JsonContent(ref: MultipleArticlesResource::class)
    )]
    // #[Parameters(factory: ListFeedParameters::class)]
    public function feed(Request $request): MultipleArticlesResource
    {
        $articles = Article::with('author', 'tags', 'favoritedBy')
            ->followedAuthor(Auth::user());

        return new MultipleArticlesResource(
            (clone $articles)
                ->orderByDesc('id')
                ->offset($request->offset)
                ->limit(min($request->query('limit', (string) self::MAX_LIMIT), self::MAX_LIMIT))
                ->get(),
            $articles->count()
        );
    }

    /**
     * Get an article.
     *
     * Get an article. Auth not required
     *
     * @param  Article  $slug  Slug of the article to get
     */
    #[Get('/{slug}')]
    #[OA\Get(path: '/articles/{slug}', operationId: 'GetArticle', tags: ['Articles'])]
    #[OA\Parameter(
        name: 'slug',
        in: 'path',
        required: true,
        description: 'Slug of the article to get'
    )]
    #[OA\Response(
        response: 200,
        description: 'Success',
        content: new OA\JsonContent(ref: SingleArticleResource::class)
    )]
    public function get(Article $slug): SingleArticleResource
    {
        return new SingleArticleResource($slug->loadCount('favoritedBy'));
    }

    /**
     * Create an article.
     *
     * Create an article. Auth is required
     */
    #[Post('/', middleware: 'auth')]
    #[OA\Post(path: '/articles', operationId: 'CreateArticle', tags: ['Articles'], security: ['BearerToken'])]
    #[OA\RequestBody(
        required: true,
        description: 'Article to create',
        content: new OA\JsonContent(ref: NewArticleRequest::class)
    )]
    #[OA\Response(
        response: 200,
        description: 'Success',
        content: new OA\JsonContent(ref: SingleArticleResource::class)
    )]
    // #[Response(factory: ErrorValidationResponse::class, statusCode: 422)]
    public function create(NewArticleRequest $request): SingleArticleResource
    {
        $article = new Article($request->input('article'));

        $article->author()->associate(Auth::user());

        $article->save();

        $tags = collect($request->input('article.tagList'))
            ->map(fn (string $t) => Tag::firstOrCreate(['name' => $t]));
        $article->tags()->attach($tags->pluck('id'));

        return new SingleArticleResource($article->loadCount('favoritedBy'));
    }

    /**
     * Update an article.
     *
     * Update an article. Auth is required
     *
     * @param  Article  $slug  Slug of the article to update
     */
    #[Put('/{slug}', middleware: ['auth', 'can:update,slug'])]
    #[OA\Put(path: '/articles/{slug}', operationId: 'UpdateArticle', tags: ['Articles'], security: ['BearerToken'])]
    #[OA\Parameter(
        name: 'slug',
        in: 'path',
        required: true,
        description: 'Slug of the article to update'
    )]
    #[OA\RequestBody(
        required: true,
        description: 'Article to update',
        content: new OA\JsonContent(ref: UpdateArticleRequest::class)
    )]
    #[OA\Response(
        response: 200,
        description: 'Success',
        content: new OA\JsonContent(ref: SingleArticleResource::class)
    )]
    // #[Response(factory: ErrorValidationResponse::class, statusCode: 422)]
    public function update(Article $slug, UpdateArticleRequest $request): SingleArticleResource
    {
        $slug->update($request->input('article'));

        return new SingleArticleResource($slug->loadCount('favoritedBy'));
    }

    /**
     * Delete an article.
     *
     * Delete an article. Auth is required
     *
     * @param  Article  $slug  Slug of the article to delete
     */
    #[Delete('/{slug}', middleware: ['auth', 'can:update,slug'])]
    #[OA\Delete(path: '/articles/{slug}', operationId: 'DeleteArticle', tags: ['Articles'], security: ['BearerToken'])]
    #[OA\Parameter(
        name: 'slug',
        in: 'path',
        required: true,
        description: 'Slug of the article to delete'
    )]
    #[OA\Response(
        response: 204,
        description: 'Success'
    )]
    public function delete(Article $slug)
    {
        $slug->delete();

        return response()->noContent();
    }

    /**
     * Favorite an article.
     *
     * Favorite an article. Auth is required
     *
     * @param  Article  $slug  Slug of the article that you want to favorite
     */
    #[Post('/{slug}/favorite', middleware: 'auth')]
    #[OA\Post(path: '/articles/{slug}/favorite', operationId: 'CreateArticleFavorite', tags: ['Favorites'], security: ['BearerToken'])]
    #[OA\Parameter(
        name: 'slug',
        in: 'path',
        required: true,
        description: 'Slug of the article that you want to favorite'
    )]
    #[OA\Response(
        response: 200,
        description: 'Success',
        content: new OA\JsonContent(ref: SingleArticleResource::class)
    )]
    public function favorite(Article $slug): SingleArticleResource
    {
        $slug->favoritedBy()->attach(Auth::id());

        return new SingleArticleResource($slug->loadCount('favoritedBy'));
    }

    /**
     * Unfavorite an article.
     *
     * Unfavorite an article. Auth is required
     *
     * @param  Article  $slug  Slug of the article that you want to unfavorite
     */
    #[Delete('{slug}/favorite', middleware: 'auth')]
    #[OA\Delete(path: '/articles/{slug}/favorite', operationId: 'DeleteArticleFavorite', tags: ['Favorites'], security: ['BearerToken'])]
    #[OA\Parameter(
        name: 'slug',
        in: 'path',
        required: true,
        description: 'Slug of the article that you want to unfavorite'
    )]
    #[OA\Response(
        response: 200,
        description: 'Success',
        content: new OA\JsonContent(ref: SingleArticleResource::class)
    )]
    public function unfavorite(Article $slug): SingleArticleResource
    {
        $slug->favoritedBy()->detach(Auth::id());

        return new SingleArticleResource($slug->loadCount('favoritedBy'));
    }
}
