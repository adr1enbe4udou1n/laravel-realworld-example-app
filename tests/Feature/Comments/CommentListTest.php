<?php

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Facades\DB;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

it('cannot list all comments of non existent article', function () {
    getJson('api/articles/test-title/comments')->assertNotFound();
});

it('can list all comments of article', function () {
    /** @var User */
    $user = User::factory()->john()->create();
    $article = Article::factory()->for($user, 'author')->create(['title' => 'Test Title']);
    Comment::factory(10)->for($user, 'author')->for($article)
        ->sequence(fn (Sequence $sequence) => ['body' => "John Comment {$sequence->index}"])
        ->create()
    ;

    actingAs($user);

    DB::enableQueryLog();

    assertSqlQueriesCountEqual(
        4,
        fn () => getJson('api/articles/test-title/comments')->assertOk()->assertJson([
            'comments' => [
                [
                    'body' => 'John Comment 9',
                    'author' => [
                        'username' => 'John Doe',
                        'bio' => 'John Bio',
                        'image' => 'https://randomuser.me/api/portraits/men/1.jpg',
                        'following' => false,
                    ],
                ],
            ],
        ])->assertJsonCount(10, 'comments')
    );
});
