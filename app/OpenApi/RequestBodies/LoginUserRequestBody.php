<?php

namespace App\OpenApi\RequestBodies;

use App\OpenApi\Schemas\LoginUserRequestSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use Vyuldashev\LaravelOpenApi\Factories\RequestBodyFactory;

class LoginUserRequestBody extends RequestBodyFactory
{
    public function build(): RequestBody
    {
        return RequestBody::create()
            ->description('Details of the new user to register')
            ->content(
                MediaType::json()->schema(LoginUserRequestSchema::ref())
            )
        ;
    }
}
