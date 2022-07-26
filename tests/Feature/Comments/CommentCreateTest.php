<?php

use App\Models\Article;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;

it('guest cannot create comment', function () {
    postJson('api/articles/test-title/comments')->assertUnauthorized();
});

it('cannot create comment to non existent article', function () {
    /** @var User */
    $user = User::factory()->john()->create();
    actingAs($user);

    postJson('api/articles/test-title/comments')->assertNotFound();
});

it('cannot create comment with invalid data', function ($data, $errors) {
    /** @var User */
    $user = User::factory()->john()->create();
    Article::factory()->for($user, 'author')->create(['title' => 'Test Title']);
    actingAs($user);

    postJson('api/articles/test-title/comments', [
        'comment' => $data,
    ])->assertJsonValidationErrors($errors);
})->with([
    [
        [
            'body' => '',
        ],
        ['body'],
    ],
]);

it('can create comment', function () {
    /** @var User */
    $user = User::factory()->john()->create();
    Article::factory()->for($user, 'author')->create(['title' => 'Test Title']);
    actingAs($user);

    postJson('api/articles/test-title/comments', [
        'comment' => ['body' => 'My New Comment'],
    ])->assertCreated()->assertJson([
        'comment' => [
            'body' => 'My New Comment',
            'author' => [
                'username' => 'John Doe',
                'bio' => 'John Bio',
                'image' => 'https://randomuser.me/api/portraits/men/1.jpg',
                'following' => false,
            ],
        ],
    ]);
});
