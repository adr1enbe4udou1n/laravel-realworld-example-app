<?php

use function Pest\Laravel\getJson;

it('can fetch openapi', fn () => getJson('api/docs.json')->assertOk());
