<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

use App\Models\Article;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

uses(Tests\TestCase::class)->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function assertSqlQueriesCountEqual(int $count, Closure $closure = null)
{
    if ($closure) {
        DB::enableQueryLog();

        $closure();
    }

    // dump(DB::getQueryLog());

    expect(count(DB::getQueryLog()))->toBe($count);

    if ($closure) {
        DB::flushQueryLog();
    }
}

function createArticles()
{
    $tag1 = Tag::create(['name' => 'Tag 1']);
    $tag2 = Tag::create(['name' => 'Tag 2']);
    $johnTag = Tag::create(['name' => 'John Tag']);
    $janeTag = Tag::create(['name' => 'Jane Tag']);

    /** @var User */
    $john = User::factory()->john()->create();

    /** @var User */
    $jane = User::factory()->jane()->create();

    $jane->followers()->attach($john->id);

    $johnFavoritedArticles = [
        'jane-article-1',
        'jane-article-2',
        'jane-article-4',
        'jane-article-8',
        'jane-article-16',
    ];

    Article::factory(30)->for($john, 'author')
        ->sequence(fn (Sequence $sequence) => ['title' => "John Article {$sequence->index}"])
        ->create([
            'description' => 'Test Description',
            'body' => 'Test Body',
        ])
        ->transform(function (Model $article) use ($tag1, $tag2, $johnTag) {
            /** @var Article $article */
            return $article->tags()->attach([$tag1->id, $tag2->id, $johnTag->id]);
        });
    Article::factory(20)->for($jane, 'author')
        ->sequence(fn (Sequence $sequence) => ['title' => "Jane Article {$sequence->index}"])
        ->create([
            'description' => 'Test Description',
            'body' => 'Test Body',
        ])
        ->transform(function (Model $article) use ($tag1, $tag2, $janeTag, $johnFavoritedArticles, $john) {
            /** @var Article $article */
            $article->tags()->attach([$tag1->id, $tag2->id, $janeTag->id]);

            if (in_array($article->slug, $johnFavoritedArticles)) {
                $article->favoritedBy()->attach($john->id);
            }

            return $article;
        });
}
