<?php

use Illuminate\Foundation\Application;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\App;
use Spatie\RouteAttributes\RouteRegistrar;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using: function () {
            Route::prefix('api')
                ->middleware('api')
                ->group(
                    function () {
                        (new RouteRegistrar(app(Router::class)))
                            ->useRootNamespace(App::getNamespace())
                            ->registerDirectory(app_path('Http/Controllers/Api'));
                    }
                );
        },
    )
    ->withMiddleware(function () {
        //
    })
    ->withExceptions(function () {
        //
    })->create();
