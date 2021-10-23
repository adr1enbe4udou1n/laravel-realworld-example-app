<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewCommentRequest;
use App\Http\Resources\MultipleCommentsResponse;
use App\Http\Resources\SingleCommentResponse;
use App\Models\Article;
use App\Models\Comment;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('articles/{slug}/comments')]
class CommentController extends Controller
{
    #[Get('/')]
    public function list(Article $article): MultipleCommentsResponse
    {
        return new MultipleCommentsResponse(null);
    }

    #[Post('/', middleware: 'auth')]
    public function create(Article $article, NewCommentRequest $request): SingleCommentResponse
    {
        return new SingleCommentResponse(null);
    }

    #[Delete('/{commentId}', middleware: 'auth')]
    public function delete(Article $article, Comment $comment)
    {
    }
}
