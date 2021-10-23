<?php

namespace App\OpenApi\RequestBodies;

use App\OpenApi\Schemas\UpdateUserRequestSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use Vyuldashev\LaravelOpenApi\Factories\RequestBodyFactory;

class UpdateUserRequestBody extends RequestBodyFactory
{
    public function build(): RequestBody
    {
        return RequestBody::create()
            ->description('User details to update. At least one field is required.')
            ->content(
                MediaType::json()->schema(UpdateUserRequestSchema::ref())
            )
        ;
    }
}
