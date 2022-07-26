<?php

use App\Models\Article;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

it('cannot get non existent article', function () {
    /** @var User */
    $user = User::factory()->john()->create();
    actingAs($user);

    getJson('api/articles/test-title')->assertNotFound();
});

it('can get article', function () {
    /** @var User */
    $user = User::factory()->john()->create();
    Article::factory()->for($user, 'author')->create(['title' => 'Test Title']);

    getJson('api/articles/test-title')->assertOk()->assertJson([
        'article' => [
            'title' => 'Test Title',
            'slug' => 'test-title',
            'author' => [
                'username' => 'John Doe',
                'bio' => 'John Bio',
                'image' => 'https://randomuser.me/api/portraits/men/1.jpg',
                'following' => false,
            ],
        ],
    ]);
});
