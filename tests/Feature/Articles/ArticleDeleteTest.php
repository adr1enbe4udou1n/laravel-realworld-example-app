<?php

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\deleteJson;

it('guest cannot delete article', function () {
    deleteJson('api/articles/test-title')->assertUnauthorized();
});

it('cannot delete non existent article', function () {
    /** @var User */
    $user = User::factory()->john()->create();
    actingAs($user);

    deleteJson('api/articles/test-title')->assertNotFound();
});

it('cannot delete article of other author', function () {
    /** @var User */
    $jane = User::factory()->jane()->create();

    Article::factory()->for($jane, 'author')->create(['title' => 'Test Title']);

    /** @var User */
    $john = User::factory()->john()->create();
    actingAs($john);

    deleteJson('api/articles/test-title')->assertForbidden();
});

it('can delete own article with all comments', function () {
    /** @var User */
    $john = User::factory()->john()->create();
    $jane = User::factory()->jane()->create();
    actingAs($john);

    Article::factory()
        ->for($john, 'author')
        ->has(
            Comment::factory()->for($john, 'author'),
        )
        ->has(
            Comment::factory()->for($jane, 'author'),
        )
        ->create(['title' => 'Test Title']);

    deleteJson('api/articles/test-title')->assertNoContent();

    assertDatabaseCount('articles', 0);
    assertDatabaseCount('comments', 0);
});
