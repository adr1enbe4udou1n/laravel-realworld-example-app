<?php

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\deleteJson;

it('guest cannot delete comment', function () {
    deleteJson('api/articles/test-title/comments/1')->assertUnauthorized();
});

it('cannot delete comment with non existent article', function () {
    /** @var User */
    $user = User::factory()->john()->create();
    actingAs($user);

    deleteJson('api/articles/test-title/comments/1')->assertNotFound();
});

it('cannot delete non existent comment', function () {
    /** @var User */
    $user = User::factory()->john()->create();
    Article::factory()->for($user, 'author')->create(['title' => 'Test Title']);

    actingAs($user);

    deleteJson('api/articles/test-title/comments/1')->assertNotFound();
});

it('cannot delete comment of other author', function () {
    /** @var User */
    $jane = User::factory()->jane()->create();
    /** @var User */
    $john = User::factory()->john()->create();

    /** @var Article */
    $article = Article::factory()->for($jane, 'author')->create(['title' => 'Test Title']);
    /** @var Comment */
    $comment = Comment::factory()->for($jane, 'author')->for($article)->create(['body' => 'Jane Comment']);

    actingAs($john);

    deleteJson("api/articles/test-title/comments/{$comment->id}")->assertForbidden();
});

it('cannot delete comment with bad article', function () {
    /** @var User */
    $user = User::factory()->jane()->create();

    /** @var Article */
    $article = Article::factory()->for($user, 'author')->create(['title' => 'Test Title']);
    /** @var Comment */
    $comment = Comment::factory()->for($user, 'author')->for($article)->create(['body' => 'John Comment']);

    Article::factory()->for($user, 'author')->create(['title' => 'Bad Title']);

    actingAs($user);

    deleteJson("api/articles/bad-title/comments/{$comment->id}")->assertForbidden();
});

it('can delete all comments of own article', function () {
    /** @var User */
    $jane = User::factory()->jane()->create();
    /** @var User */
    $john = User::factory()->john()->create();

    /** @var Article */
    $article = Article::factory()->for($john, 'author')->create(['title' => 'Test Title']);
    /** @var Comment */
    $comment = Comment::factory()->for($jane, 'author')->for($article)->create(['body' => 'Jane Comment']);

    actingAs($john);

    deleteJson("api/articles/test-title/comments/{$comment->id}")->assertNoContent();

    assertDatabaseCount('comments', 0);
});

it('can delete own comment', function () {
    /** @var User */
    $jane = User::factory()->jane()->create();
    /** @var User */
    $john = User::factory()->john()->create();

    /** @var Article */
    $article = Article::factory()->for($jane, 'author')->create(['title' => 'Test Title']);
    /** @var Comment */
    $comment = Comment::factory()->for($john, 'author')->for($article)->create(['body' => 'John Comment']);

    actingAs($john);

    deleteJson("api/articles/test-title/comments/{$comment->id}")->assertNoContent();

    assertDatabaseCount('comments', 0);
});
