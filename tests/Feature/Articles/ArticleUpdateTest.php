<?php

it('guest cannot update article', function () {
})->skip();

it('cannot update non existent article', function () {
})->skip();

it('cannot update article with invalid data', function ($data) {
})->with([[
    [
        'title' => 'Test Title',
        'description' => 'Test Description',
        'body' => '',
    ],
]])->skip();

it('cannot update article of other author', function () {
})->skip();

it('can update own article', function () {
})->skip();
