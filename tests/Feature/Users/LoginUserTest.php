<?php

use App\Models\User;
use App\Support\Jwt;

use function Pest\Laravel\postJson;

it('user cannot login with invalid data', function ($credentials) {
    User::factory()->john()->create();

    postJson('api/users/login', [
        'user' => $credentials,
    ])->assertStatus(400);
})->with([
    [
        [
            'email' => 'jane.doe@example.com',
            'password' => 'password',
        ],
    ],
    [
        [
            'email' => 'john.doe@example.com',
            'password' => 'badpassword',
        ],
    ],
]);

it('user can login', function () {
    User::factory()->john()->create();

    $content = postJson('api/users/login', [
        'user' => [
            'email' => 'john.doe@example.com',
            'password' => 'password',
        ],
    ])->assertOk()->assertJson([
        'user' => [
            'username' => 'John Doe',
            'email' => 'john.doe@example.com',
        ],
    ])->json();

    $token = app(Jwt::class)->parse($content['user']['token']);

    expect($token->claims()->get('email'))->toBe('john.doe@example.com');
});
