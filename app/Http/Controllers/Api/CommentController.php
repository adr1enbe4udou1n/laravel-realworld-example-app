<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewCommentRequest;
use App\Http\Resources\MultipleCommentsResource;
use App\Http\Resources\SingleCommentResource;
use App\Models\Article;
use App\Models\Comment;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;
use Vyuldashev\LaravelOpenApi\Attributes\Operation;
use Vyuldashev\LaravelOpenApi\Attributes\PathItem;

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
    #[Operation(tags: ['Comments'])]
    public function list(Article $slug): MultipleCommentsResource
    {
        return new MultipleCommentsResource(null);
    }

    /**
     * Create a comment for an article.
     *
     * Create a comment for an article. Auth is required
     *
     * @param Article $slug Slug of the article that you want to create a comment for
     */
    #[Post('/', middleware: 'auth')]
    #[Operation(tags: ['Comments'], security: 'BearerToken')]
    public function create(Article $slug, NewCommentRequest $request): SingleCommentResource
    {
        return new SingleCommentResource(null);
    }

    /**
     * Delete a comment for an article.
     *
     * Delete a comment for an article. Auth is required
     *
     * @param Article $slug      Slug of the article that you want to delete a comment for
     * @param Comment $commentId ID of the comment you want to delete
     */
    #[Delete('/{commentId}', middleware: 'auth')]
    #[Operation(tags: ['Comments'], security: 'BearerToken')]
    public function delete(Article $slug, Comment $commentId)
    {
    }
}
