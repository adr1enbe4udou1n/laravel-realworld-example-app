<?php

use App\Models\User;
use App\Support\Jwt;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;

it('cannot register with invalid data', function ($data, $errors) {
    post('api/users', [
        'user' => $data,
    ])->assertSessionHasErrors($errors);
})->with([
    [
        [
            'email' => 'john.doe',
            'username' => 'John Doe',
            'password' => 'password',
        ],
        ['email'],
    ], [
        [
            'email' => 'john.doe@example.com',
        ],
        ['username', 'password'],
    ], [
        [
            'email' => 'john.doe@example.com',
            'username' => 'John Doe',
            'password' => 'pass',
        ],
        ['password'],
    ],
]);

it('cannot register twice', function () {
    User::factory()->john()->create();

    post('api/users', [
        'user' => [
            'email' => 'john.doe@example.com',
            'username' => 'John Doe',
            'password' => 'password',
        ],
    ])->assertSessionHasErrors(['email']);
});

it('can register', function () {
    $content = post('api/users', [
        'user' => [
            'email' => 'john.doe@example.com',
            'username' => 'John Doe',
            'password' => 'password',
        ],
    ])->assertCreated()->assertJson([
        'user' => [
            'username' => 'John Doe',
            'email' => 'john.doe@example.com',
            'bio' => null,
            'image' => null,
        ],
    ])->json();

    $token = app(Jwt::class)->parse($content['user']['token']);

    expect($token->claims()->get('email'))->toBe('john.doe@example.com');

    assertDatabaseHas('users', ['email' => 'john.doe@example.com']);
});
