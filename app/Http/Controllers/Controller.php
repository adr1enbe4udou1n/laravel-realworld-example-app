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
class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;
}
