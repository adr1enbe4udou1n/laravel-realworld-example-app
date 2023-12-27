<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\MultipleArticlesResource;
use App\Http\Resources\SingleArticleResource;
use App\Models\Article;
use App\Models\Tag;
use App\OpenApi\Parameters\ListArticlesParameters;
use App\OpenApi\Parameters\ListFeedParameters;
use App\OpenApi\RequestBodies\NewArticleRequestBody;
use App\OpenApi\RequestBodies\UpdateArticleRequestBody;
use App\OpenApi\Responses\ErrorValidationResponse;
use App\OpenApi\Responses\MultipleArticlesResponse;
use App\OpenApi\Responses\NoContentResponse;
use App\OpenApi\Responses\SingleArticleResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Put;
use Vyuldashev\LaravelOpenApi\Attributes\Operation;
use Vyuldashev\LaravelOpenApi\Attributes\Parameters;
use Vyuldashev\LaravelOpenApi\Attributes\PathItem;
use Vyuldashev\LaravelOpenApi\Attributes\RequestBody;
use Vyuldashev\LaravelOpenApi\Attributes\Response;

#[Prefix('articles')]
#[PathItem]
class ArticleController extends Controller
{
    public const MAX_LIMIT = 20;

    /**
     * Get recent articles globally.
     *
     * Get most recent articles globally. Use query parameters to filter results. Auth is optional
     */
    #[Get('/')]
    #[Operation('GetArticles', tags: ['Articles'])]
    #[Parameters(factory: ListArticlesParameters::class)]
    #[Response(factory: MultipleArticlesResponse::class, statusCode: 200)]
    public function list(Request $request): MultipleArticlesResource
    {
        $articles = Article::with('author', 'tags', 'favoritedBy')
            ->withCount('favoritedBy')
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
    #[Operation('GetArticlesFeed', tags: ['Articles'], security: 'BearerToken')]
    #[Parameters(factory: ListFeedParameters::class)]
    #[Response(factory: MultipleArticlesResponse::class, statusCode: 200)]
    public function feed(Request $request): MultipleArticlesResource
    {
        $articles = Article::with('author', 'tags', 'favoritedBy')
            ->withCount('favoritedBy')
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
     * @param  Article  $slug Slug of the article to get
     */
    #[Get('/{slug}')]
    #[Operation('GetArticle', tags: ['Articles'])]
    #[Response(factory: SingleArticleResponse::class, statusCode: 200)]
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
    #[Operation('CreateArticle', tags: ['Articles'], security: 'BearerToken')]
    #[RequestBody(factory: NewArticleRequestBody::class)]
    #[Response(factory: SingleArticleResponse::class, statusCode: 200)]
    #[Response(factory: ErrorValidationResponse::class, statusCode: 422)]
    public function create(NewArticleRequest $request): SingleArticleResource
    {
        $article = new Article($request->input('article'));

        $article->author()->associate(Auth::user());

        $article->save();

        $tags = collect($request->input('article.tagList'))
            ->map(fn(string $t) => Tag::firstOrCreate(['name' => $t]));
        $article->tags()->attach($tags->pluck('id'));

        return new SingleArticleResource($article->loadCount('favoritedBy'));
    }

    /**
     * Update an article.
     *
     * Update an article. Auth is required
     *
     * @param  Article  $slug Slug of the article to update
     */
    #[Put('/{slug}', middleware: ['auth', 'can:update,slug'])]
    #[Operation('UpdateArticle', tags: ['Articles'], security: 'BearerToken')]
    #[RequestBody(factory: UpdateArticleRequestBody::class)]
    #[Response(factory: SingleArticleResponse::class, statusCode: 200)]
    #[Response(factory: ErrorValidationResponse::class, statusCode: 422)]
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
     * @param  Article  $slug Slug of the article to delete
     */
    #[Delete('/{slug}', middleware: ['auth', 'can:update,slug'])]
    #[Operation('DeleteArticle', tags: ['Articles'], security: 'BearerToken')]
    #[Response(factory: NoContentResponse::class, statusCode: 204)]
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
     * @param  Article  $slug Slug of the article that you want to favorite
     */
    #[Post('/{slug}/favorite', middleware: 'auth')]
    #[Operation('CreateArticleFavorite', tags: ['Favorites'], security: 'BearerToken')]
    #[Response(factory: SingleArticleResponse::class, statusCode: 200)]
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
     * @param  Article  $slug Slug of the article that you want to unfavorite
     */
    #[Delete('{slug}/favorite', middleware: 'auth')]
    #[Operation('DeleteArticleFavorite', tags: ['Favorites'], security: 'BearerToken')]
    #[Response(factory: SingleArticleResponse::class, statusCode: 200)]
    public function unfavorite(Article $slug): SingleArticleResource
    {
        $slug->favoritedBy()->detach(Auth::id());

        return new SingleArticleResource($slug->loadCount('favoritedBy'));
    }
}
