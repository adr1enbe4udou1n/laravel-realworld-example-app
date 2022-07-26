<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\postJson;

it('guest cannot follow profile', function () {
    User::factory()->john()->create();

    postJson('api/profiles/celeb_John Doe/follow')->assertUnauthorized();
});

it('cannot follow non existent profile', function () {
    /** @var User */
    $user = User::factory()->john()->create();
    actingAs($user);

    postJson('api/profiles/celeb_Jane Doe/follow')->assertNotFound();
});

it('can follow profile', function () {
    /** @var User */
    $john = User::factory()->john()->create();

    User::factory()->jane()->create();

    actingAs($john);

    postJson('api/profiles/celeb_Jane Doe/follow')->assertOk()->assertJson([
        'profile' => [
            'username' => 'Jane Doe',
            'bio' => 'Jane Bio',
            'image' => 'https://randomuser.me/api/portraits/women/1.jpg',
            'following' => true,
        ],
    ]);

    assertDatabaseCount('follower_user', 1);
});

it('can unfollow profile', function () {
    /** @var User */
    $john = User::factory()->john()->create();

    /** @var User */
    $jane = User::factory()->jane()->create();
    $jane->followers()->attach($john);

    actingAs($john);

    deleteJson('api/profiles/celeb_Jane Doe/follow')->assertOk()->assertJson([
        'profile' => [
            'username' => 'Jane Doe',
            'bio' => 'Jane Bio',
            'image' => 'https://randomuser.me/api/portraits/women/1.jpg',
            'following' => false,
        ],
    ]);

    assertDatabaseCount('follower_user', 0);
});
