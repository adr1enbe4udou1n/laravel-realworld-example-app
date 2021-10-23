<?php

namespace App\OpenApi\SecuritySchemes;

use GoldSpecDigital\ObjectOrientedOAS\Objects\SecurityScheme;
use Vyuldashev\LaravelOpenApi\Factories\SecuritySchemeFactory;

class BearerToken extends SecuritySchemeFactory
{
    public function build(): SecurityScheme
    {
        return SecurityScheme::create('BearerToken')
            ->type(SecurityScheme::TYPE_API_KEY)
            ->scheme('bearer')
            ->bearerFormat('JWT')
            ->name('Authorization')
            ->in(SecurityScheme::IN_HEADER)
        ;
    }
}
