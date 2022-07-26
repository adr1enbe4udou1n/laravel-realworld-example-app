<?php

use App\Models\Article;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\postJson;

it('guest cannot favorite article', function () {
    postJson('api/articles/test-title/favorite')->assertUnauthorized();
});

it('cannot favorite non existent article', function () {
    /** @var User */
    $user = User::factory()->john()->create();
    actingAs($user);

    postJson('api/articles/test-title/favorite')->assertNotFound();
});

it('can favorite article', function () {
    /** @var User */
    $user = User::factory()->john()->create();
    Article::factory()->for($user, 'author')->create(['title' => 'Test Title']);

    actingAs($user);

    postJson('api/articles/test-title/favorite')->assertOk()->assertJson([
        'article' => [
            'title' => 'Test Title',
            'slug' => 'test-title',
            'favorited' => true,
            'favoritesCount' => 1,
        ],
    ]);
});

it('can unfavorite article', function () {
    /** @var User */
    $user = User::factory()->john()->create();

    /** @var Article */
    $article = Article::factory()->for($user, 'author')->create(['title' => 'Test Title']);
    $article->favoritedBy()->attach($user->id);

    actingAs($user);

    deleteJson('api/articles/test-title/favorite')->assertOk()->assertJson([
        'article' => [
            'title' => 'Test Title',
            'slug' => 'test-title',
            'favorited' => false,
            'favoritesCount' => 0,
        ],
    ]);
});
