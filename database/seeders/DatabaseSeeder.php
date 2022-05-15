<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Comment;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $users = User::factory(50)->create();

        $users->each(
            fn (User $user) => $user->followers()->sync($users->random(random_int(1, 3)))
        );

        $tags = Tag::factory(30)->create();

        Article::factory(500)
            ->sequence(fn () => ['author_id' => $users->random()->getKey()])
            ->has(
                Comment::factory(random_int(5, 10))
                    ->sequence(fn () => ['author_id' => $users->random()->getKey()])
            )
            ->create()
            ->each(
                function (Article $article) use ($users, $tags) {
                    $article->tags()->sync($tags->random(random_int(2, 4)));
                    $article->favoritedBy()->sync($users->random(random_int(1, 10)));
                }
            )
        ;
    }
}
