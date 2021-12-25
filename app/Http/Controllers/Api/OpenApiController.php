<?php

namespace App\Http\Controllers\Api;

use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;
use GoldSpecDigital\ObjectOrientedOAS\OpenApi;
use Illuminate\Support\Str;
use Spatie\RouteAttributes\Attributes\Get;
use Vyuldashev\LaravelOpenApi\Generator;

class OpenApiController
{
    #[Get('/docs.json')]
    public function show(Generator $generator): OpenApi
    {
        $doc = $generator->generate();

        return $doc->paths(...collect($doc->paths)->map(
            fn (PathItem $item) => $item->route(Str::of($item->route)->replace('/api', ''))
        )->toArray());
    }
}
