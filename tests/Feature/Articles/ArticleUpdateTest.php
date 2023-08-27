<?php

use App\Models\Article;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\putJson;

it('guest cannot update article', function () {
    putJson('api/articles/test-title')->assertUnauthorized();
});

it('cannot update non existent article', function () {
    /** @var User */
    $user = User::factory()->john()->create();
    actingAs($user);

    putJson('api/articles/test-title')->assertNotFound();
});

it('cannot update article with invalid data', function ($data, $errors) {
    /** @var User */
    $user = User::factory()->john()->create();
    actingAs($user);

    Article::factory()->for($user, 'author')->create(['title' => 'Test Title']);

    putJson('api/articles/test-title', [
        'article' => $data,
    ])->assertJsonValidationErrors($errors);
})->with([
    [
        [
            'title' => 'Test Title',
            'body' => '',
        ],
        ['body'],
    ],
]);

it('cannot update article of other author', function () {
    /** @var User */
    $jane = User::factory()->jane()->create();

    Article::factory()->for($jane, 'author')->create(['title' => 'Test Title']);

    /** @var User */
    $john = User::factory()->john()->create();
    actingAs($john);

    putJson('api/articles/test-title')->assertForbidden();
});

it('can update own article', function () {
    /** @var User */
    $user = User::factory()->john()->create();
    Article::factory()->for($user, 'author')->create(['title' => 'Test Title']);

    actingAs($user);

    putJson('api/articles/test-title', [
        'article' => [
            'title' => 'New Title',
            'description' => 'New Description',
            'body' => 'New Body',
        ],
    ])->assertOk()->assertJson([
        'article' => [
            'title' => 'New Title',
            'slug' => 'test-title',
            'description' => 'New Description',
            'body' => 'New Body',
            'author' => [
                'username' => 'John Doe',
                'bio' => 'John Bio',
                'image' => 'https://randomuser.me/api/portraits/men/1.jpg',
                'following' => false,
            ],
        ],
    ]);

    assertDatabaseHas('articles', [
        'title' => 'New Title',
        'description' => 'New Description',
        'body' => 'New Body',
    ]);
});
