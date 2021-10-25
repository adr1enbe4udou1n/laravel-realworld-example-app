<?php

use function Pest\Laravel\getJson;

it('can paginate articles', function () {
    createArticles();

    getJson('api/articles?limit=10&offset=20')->assertOk()->assertJson([
        'articles' => [
            [
                'title' => 'John Article 29',
                'slug' => 'john-article-29',
                'description' => 'Test Description',
                'body' => 'Test Body',
                'author' => [
                    'username' => 'John Doe',
                    'bio' => 'John Bio',
                    'image' => 'https://randomuser.me/api/portraits/men/1.jpg',
                    'following' => false,
                ],
                'tagList' => ['John Tag', 'Tag 1', 'Tag 2'],
                'favorited' => false,
                'favoritesCount' => 0,
            ],
        ],
    ])->assertJsonCount(10, 'articles');
});

it('can filter articles by author', function () {
})->skip();

it('can filter articles by favorited', function () {
})->skip();

it('can filter articles by tag', function () {
})->skip();

it('guest cannot paginate feed', function () {
})->skip();

it('can paginate feed', function () {
})->skip();
