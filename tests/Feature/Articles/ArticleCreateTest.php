<?php

use App\Models\Article;
use App\Models\Tag;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;

it('guest cannot create article', function () {
    postJson('api/articles')->assertUnauthorized();
});

it('cannot create article with invalid data', function ($data, $errors) {
    /** @var User */
    $user = User::factory()->john()->create();
    actingAs($user);

    postJson('api/articles', [
        'article' => $data,
    ])->assertJsonValidationErrors($errors);
})->with([
    [
        [
            'title' => '',
            'description' => 'Test Description',
            'body' => 'Test Body',
        ],
        ['title'],
    ], [
        [
            'title' => 'Test Title',
            'description' => '',
            'body' => 'Test Body',
        ],
        ['description'],
    ], [
        [
            'title' => 'Test Title',
            'description' => 'Test Description',
            'body' => '',
        ],
        ['body'],
    ],
]);

it('cannot create article with same title', function () {
    /** @var User */
    $user = User::factory()->john()->create();

    Article::factory()
        ->for($user, 'author')
        ->create([
            'title' => 'Test Title',
        ]);

    actingAs($user);

    postJson('api/articles', [
        'article' => [
            'title' => 'Test Title',
            'description' => 'Test Description',
            'body' => 'Test Body',
        ],
    ])->assertJsonValidationErrors(['slug']);
});

it('can create article', function () {
    Tag::create(['name' => 'Existing Tag']);

    /** @var User */
    $user = User::factory()->john()->create();

    actingAs($user);

    postJson('api/articles', [
        'article' => [
            'title' => 'Test Title',
            'description' => 'Test Description',
            'body' => 'Test Body',
            'tagList' => ['Tag 1', 'Tag 2', 'Existing Tag'],
        ],
    ])->assertCreated()->assertJson([
        'article' => [
            'title' => 'Test Title',
            'slug' => 'test-title',
            'description' => 'Test Description',
            'body' => 'Test Body',
            'author' => [
                'username' => 'John Doe',
                'bio' => 'John Bio',
                'image' => 'https://randomuser.me/api/portraits/men/1.jpg',
                'following' => false,
            ],
            'tagList' => ['Existing Tag', 'Tag 1', 'Tag 2'],
            'favorited' => false,
            'favoritesCount' => 0,
        ],
    ]);

    assertDatabaseHas('articles', [
        'slug' => 'test-title',
    ]);

    assertDatabaseCount('tags', 3);
});
