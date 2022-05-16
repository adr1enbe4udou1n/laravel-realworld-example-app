<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewCommentRequest;
use App\Http\Resources\MultipleCommentsResource;
use App\Http\Resources\SingleCommentResource;
use App\Models\Article;
use App\Models\Comment;
use App\OpenApi\RequestBodies\NewCommentRequestBody;
use App\OpenApi\Responses\ErrorValidationResponse;
use App\OpenApi\Responses\MultipleCommentsResponse;
use App\OpenApi\Responses\NoContentResponse;
use App\OpenApi\Responses\SingleCommentResponse;
use Illuminate\Support\Facades\Auth;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;
use Vyuldashev\LaravelOpenApi\Attributes\Operation;
use Vyuldashev\LaravelOpenApi\Attributes\PathItem;
use Vyuldashev\LaravelOpenApi\Attributes\RequestBody;
use Vyuldashev\LaravelOpenApi\Attributes\Response;

#[Prefix('articles/{slug}/comments')]
#[PathItem]
class CommentController extends Controller
{
    /**
     * Get comments for an article.
     *
     * Get the comments for an article. Auth is optional
     *
     * @param Article $slug Slug of the article that you want to get comments for
     */
    #[Get('/')]
    #[Operation('GetArticleComments', tags: ['Comments'])]
    #[Response(factory: MultipleCommentsResponse::class, statusCode: 200)]
    public function list(Article $slug): MultipleCommentsResource
    {
        return new MultipleCommentsResource($slug->comments()->with('author.followers')->orderByDesc('id')->get());
    }

    /**
     * Create a comment for an article.
     *
     * Create a comment for an article. Auth is required
     *
     * @param Article $slug Slug of the article that you want to create a comment for
     */
    #[Post('/', middleware: 'auth')]
    #[Operation('CreateArticleComment', tags: ['Comments'], security: 'BearerToken')]
    #[RequestBody(factory: NewCommentRequestBody::class)]
    #[Response(factory: SingleCommentResponse::class, statusCode: 200)]
    #[Response(factory: ErrorValidationResponse::class, statusCode: 422)]
    public function create(Article $slug, NewCommentRequest $request): SingleCommentResource
    {
        $comment = new Comment($request->input('comment'));

        $comment->author()->associate(Auth::user());
        $comment->article()->associate($slug);
        $comment->save();

        return new SingleCommentResource($comment);
    }

    /**
     * Delete a comment for an article.
     *
     * Delete a comment for an article. Auth is required
     *
     * @param Article $slug      Slug of the article that you want to delete a comment for
     * @param Comment $commentId ID of the comment you want to delete
     */
    #[Delete('/{commentId}', middleware: ['auth', 'can:delete,commentId'])]
    #[Operation('DeleteArticleComment', tags: ['Comments'], security: 'BearerToken')]
    #[Response(factory: NoContentResponse::class, statusCode: 204)]
    public function delete(Article $slug, Comment $commentId)
    {
        abort_if($slug->id !== $commentId->article_id, 403);

        $commentId->delete();

        return response()->noContent();
    }
}
