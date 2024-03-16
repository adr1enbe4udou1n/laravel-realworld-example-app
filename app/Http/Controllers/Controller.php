<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Attributes as OA;

#[OA\Info(
    title: 'Conduit API',
    version: '1.0.0',
    description: 'Conduit is a social blogging site (like Medium) built on top of Laravel. This is the API backend.'
)]
#[OA\Server(
    url: '/api',
    description: 'Conduit API server'
)]
#[OA\SecurityScheme(
    type: 'apiKey',
    scheme: 'bearer',
    in: 'header',
    name: 'Authorization',
    bearerFormat: 'JWT',
    securityScheme: 'BearerToken'
)]
#[OA\Schema(
    schema: "ErrorValidationResponse",
    type: "object",
    properties: [
        new OA\Property(
            property: "message",
            type: "string"
        ),
        new OA\Property(
            property: "errors",
            type: "object",
            additionalProperties: new OA\AdditionalProperties(
                type: "array",
                items: new OA\Items(type: "string")
            )
        )
    ]
)]
class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;
}
