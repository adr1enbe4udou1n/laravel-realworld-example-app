<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewCommentRequest;
use App\Http\Resources\MultipleCommentsResource;
use App\Http\Resources\SingleCommentResource;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;
use OpenApi\Attributes as OA;

#[Prefix('articles/{slug}/comments')]
class CommentController extends Controller
{
    /**
     * Get comments for an article.
     *
     * Get the comments for an article. Auth is optional
     *
     * @param  Article  $slug  Slug of the article that you want to get comments for
     */
    #[Get('/')]
    #[OA\Get(
        path: '/articles/{slug}/comments',
        operationId: 'GetArticleComments',
        tags: ['Comments']
    )]
    #[OA\Response(
        response: 200,
        description: 'Success',
        content: new OA\JsonContent(ref: MultipleCommentsResource::class)
    )]
    public function list(Article $slug): MultipleCommentsResource
    {
        return new MultipleCommentsResource($slug->comments()->with('author.followers')->orderByDesc('id')->get());
    }

    /**
     * Create a comment for an article.
     *
     * Create a comment for an article. Auth is required
     *
     * @param  Article  $slug  Slug of the article that you want to create a comment for
     */
    #[Post('/', middleware: 'auth')]
    #[OA\Post(
        path: '/articles/{slug}/comments',
        operationId: 'CreateArticleComment',
        tags: ['Comments'],
        security: ['BearerToken']
    )]
    #[OA\Response(
        response: 200,
        description: 'Success',
        content: new OA\JsonContent(ref: SingleCommentResource::class)
    )]
    // #[RequestBody(factory: NewCommentRequestBody::class)]
    // #[Response(factory: ErrorValidationResponse::class, statusCode: 422)]
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
     * @param  Article  $slug  Slug of the article that you want to delete a comment for
     * @param  Comment  $commentId  ID of the comment you want to delete
     */
    #[Delete('/{commentId}', middleware: ['auth', 'can:delete,commentId'])]
    #[OA\Delete(
        path: '/articles/{slug}/comments/{commentId}',
        operationId: 'DeleteArticleComment',
        tags: ['Comments'],
        security: ['BearerToken']
    )]
    #[OA\Response(response: 204, description: 'Success')]
    public function delete(Article $slug, Comment $commentId)
    {
        abort_if($slug->id !== $commentId->article_id, 403);

        $commentId->delete();

        return response()->noContent();
    }
}
