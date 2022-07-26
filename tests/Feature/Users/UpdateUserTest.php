<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\putJson;

it('guest user cannot update infos', function () {
    putJson('api/user')->assertUnauthorized();
});

it('user cannot update infos with invalid data', function ($data, $errors) {
    /** @var User */
    $user = User::factory()->john()->create();

    actingAs($user);

    putJson('api/user', [
        'user' => $data,
    ])->assertJsonValidationErrors($errors);
})->with([
    [
        [
            'username' => 'John Doe',
            'email' => 'john.doe',
            'bio' => 'My Bio',
        ],
        ['email'],
    ], [
        [
            'username' => '',
            'email' => 'john.doe@example.com',
            'bio' => 'My Bio',
        ],
        ['username'],
    ],
]);

it('user cannot update with already used email', function () {
    User::factory()->jane()->create();

    /** @var User */
    $user = User::factory()->john()->create();

    actingAs($user);

    putJson('api/user', [
        'user' => [
            'username' => 'John Doe',
            'email' => 'jane.doe@example.com',
        ],
    ])->assertJsonValidationErrors(['email']);
});

it('user can update infos', function () {
    /** @var User */
    $user = User::factory()->john()->create();

    actingAs($user);

    putJson('api/user', [
        'user' => [
            'username' => 'Jane Doe',
            'email' => 'john.doe@example.com',
            'bio' => 'My New Bio',
            'image' => 'https://randomuser.me/api/portraits/men/2.jpg',
        ],
    ])->assertOk()->assertJson([
        'user' => [
            'username' => 'Jane Doe',
            'email' => 'john.doe@example.com',
            'bio' => 'My New Bio',
            'image' => 'https://randomuser.me/api/portraits/men/2.jpg',
        ],
    ]);

    assertDatabaseHas('users', [
        'name' => 'Jane Doe',
        'email' => 'john.doe@example.com',
        'bio' => 'My New Bio',
        'image' => 'https://randomuser.me/api/portraits/men/2.jpg',
    ]);
});
