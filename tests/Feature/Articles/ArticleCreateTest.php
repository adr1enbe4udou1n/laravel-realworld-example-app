<?php

it('guest cannot create article', function () {
})->skip();

it('cannot create article with invalid data', function ($data) {
})->with([
    [
        [
            'title' => '',
            'description' => 'Test Description',
            'body' => 'Test Body',
        ],
    ], [
        [
            'title' => 'Test Title',
            'description' => '',
            'body' => 'Test Body',
        ],
    ], [
        [
            'title' => 'Test Title',
            'description' => 'Test Description',
            'body' => '',
        ],
    ],
])->skip();

it('cannot create article with same title', function () {
})->skip();

it('can create article', function () {
})->skip();
