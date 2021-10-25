<?php

use App\Models\User;
use function Pest\Laravel\actingAs;
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
        'articlesCount' => 50,
    ])->assertJsonCount(10, 'articles');
});

it('can filter articles by author', function () {
    createArticles();

    getJson('api/articles?limit=10&offset=0&author=john')->assertOk()->assertJson([
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
        'articlesCount' => 30,
    ])->assertJsonCount(10, 'articles');
});

it('can filter articles by tag', function () {
    createArticles();

    getJson('api/articles?limit=10&offset=0&tag=jane')->assertOk()->assertJson([
        'articles' => [
            [
                'title' => 'Jane Article 19',
                'slug' => 'jane-article-19',
                'description' => 'Test Description',
                'body' => 'Test Body',
                'author' => [
                    'username' => 'Jane Doe',
                    'bio' => 'Jane Bio',
                    'image' => 'https://randomuser.me/api/portraits/women/1.jpg',
                    'following' => false,
                ],
                'tagList' => ['Jane Tag', 'Tag 1', 'Tag 2'],
                'favorited' => false,
                'favoritesCount' => 0,
            ],
        ],
        'articlesCount' => 20,
    ])->assertJsonCount(10, 'articles');
});

it('can filter articles by favorited', function () {
    createArticles();

    actingAs(User::firstWhere(['name' => 'John Doe']));

    getJson('api/articles?limit=10&offset=0&favorited=john')->assertOk()->assertJson([
        'articles' => [
            [
                'title' => 'Jane Article 16',
                'slug' => 'jane-article-16',
                'description' => 'Test Description',
                'body' => 'Test Body',
                'author' => [
                    'username' => 'Jane Doe',
                    'bio' => 'Jane Bio',
                    'image' => 'https://randomuser.me/api/portraits/women/1.jpg',
                    'following' => true,
                ],
                'tagList' => ['Jane Tag', 'Tag 1', 'Tag 2'],
                'favorited' => true,
                'favoritesCount' => 1,
            ],
        ],
        'articlesCount' => 5,
    ])->assertJsonCount(5, 'articles');
});

it('guest cannot paginate feed', function () {
    getJson('api/articles/feed')->assertUnauthorized();
});

it('can paginate feed', function () {
    createArticles();

    actingAs(User::firstWhere(['name' => 'John Doe']));

    getJson('api/articles/feed?limit=10&offset=0')->assertOk()->assertJson([
        'articles' => [
            [
                'title' => 'Jane Article 19',
                'slug' => 'jane-article-19',
                'description' => 'Test Description',
                'body' => 'Test Body',
                'author' => [
                    'username' => 'Jane Doe',
                    'bio' => 'Jane Bio',
                    'image' => 'https://randomuser.me/api/portraits/women/1.jpg',
                    'following' => true,
                ],
                'tagList' => ['Jane Tag', 'Tag 1', 'Tag 2'],
                'favorited' => false,
                'favoritesCount' => 0,
            ],
        ],
        'articlesCount' => 20,
    ])->assertJsonCount(10, 'articles');
});
