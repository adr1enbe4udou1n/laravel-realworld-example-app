<?php

it('user cannot login with invalid data', function ($credentials) {
})->with([[
    [
        'email' => 'jane.doe@example.com',
        'password' => 'password',
    ],
]])->skip();

it('user can login', function () {
})->skip();
