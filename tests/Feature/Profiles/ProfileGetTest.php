<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

it('cannot get non existent profile', function () {
    getJson('api/profiles/John Doe')->assertNotFound();
});

it('can get profile', function () {
    User::factory()->john()->create();

    getJson('api/profiles/John Doe')->assertOk()->assertJson([
        'profile' => [
            'username' => 'John Doe',
            'bio' => 'John Bio',
            'image' => 'https://randomuser.me/api/portraits/men/1.jpg',
            'following' => false,
        ],
    ]);
});

it('can get followed profile', function () {
    /** @var User */
    $john = User::factory()->john()->create();

    /** @var User */
    $jane = User::factory()->jane()->create();
    $john->followers()->attach($jane);

    actingAs($jane);

    getJson('api/profiles/John Doe')->assertOk()->assertJson([
        'profile' => [
            'username' => 'John Doe',
            'bio' => 'John Bio',
            'image' => 'https://randomuser.me/api/portraits/men/1.jpg',
            'following' => true,
        ],
    ]);
});
