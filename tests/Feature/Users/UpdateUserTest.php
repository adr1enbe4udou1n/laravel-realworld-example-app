<?php

it('guest user cannot update infos', function () {
})->skip();

it('user cannot update infos with invalid data', function ($data) {
})->with([[
    [
        'username' => 'John Doe',
        'email' => 'john.doe',
        'bio' => 'My Bio',
    ],
    [
        'username' => '',
        'email' => 'john.doe@example.com',
        'bio' => 'My Bio',
    ],
]])->skip();

it('user cannot update with already used email', function () {
})->skip();

it('user can update infos', function () {
})->skip();
