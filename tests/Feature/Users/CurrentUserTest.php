<?php

use App\Models\User;
use App\Support\Jwt;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;
use function Pest\Laravel\withHeaders;

it('guest cannot fetch infos', function () {
    getJson('api/user')->assertUnauthorized();
});

it('user can fetch infos', function () {
    /** @var User */
    $user = User::factory()->john()->create();

    actingAs($user);

    getJson('api/user')->assertOk()->assertJson([
        'user' => [
            'username' => 'John Doe',
            'email' => 'john.doe@example.com',
        ],
    ]);
});

it('user can fetch infos with valid token', function () {
    /** @var User */
    $user = User::factory()->john()->create();
    $token = app(Jwt::class)->generate($user);

    withHeaders([
        'Authorization' => "Token {$token}",
    ])->getJson('api/user')->assertOk()->assertJson([
        'user' => [
            'username' => 'John Doe',
            'email' => 'john.doe@example.com',
        ],
    ]);
});
