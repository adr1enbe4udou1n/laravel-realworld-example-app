<?php

namespace App\OpenApi\RequestBodies;

use App\OpenApi\Schemas\NewUserRequestSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use Vyuldashev\LaravelOpenApi\Factories\RequestBodyFactory;

class NewUserRequestBody extends RequestBodyFactory
{
    public function build(): RequestBody
    {
        return RequestBody::create()
            ->description('Details of the new user to register')
            ->content(
                MediaType::json()->schema(NewUserRequestSchema::ref())
            )
        ;
    }
}
