<?php

it('cannot register with invalid data', function ($data) {
})->with([[
    [
        'email' => 'john.doe',
        'username' => 'John Doe',
        'password' => 'password',
    ],
    [
        'email' => 'john.doe@example.com',
    ],
    [
        'email' => 'john.doe@example.com',
        'username' => 'John Doe',
        'password' => 'pass',
    ],
]])->skip();

it('cannot register twice', function () {
})->skip();

it('can register', function () {
})->skip();
