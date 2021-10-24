<?php

it('guest cannot create comment', function () {
})->skip();

it('cannot create comment to non existent article', function () {
})->skip();

it('cannot create comment with invalid data', function ($data) {
})->with([
    [
        [
            'body' => '',
        ],
    ],
])->skip();

it('can create comment', function () {
})->skip();
