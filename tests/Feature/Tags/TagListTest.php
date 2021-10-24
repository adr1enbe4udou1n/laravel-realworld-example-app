<?php

use App\Models\Tag;
use function Pest\Laravel\getJson;

it('can list all tags', function () {
    Tag::create(['name' => 'Tag 3']);
    Tag::create(['name' => 'Tag 2']);
    Tag::create(['name' => 'Tag 1']);

    getJson('api/tags')->assertOk()->assertJson([
        'tags' => ['Tag 1', 'Tag 2', 'Tag 3'],
    ]);
});
