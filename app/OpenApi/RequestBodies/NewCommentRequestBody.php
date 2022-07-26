<?php

namespace App\OpenApi\RequestBodies;

use App\OpenApi\Schemas\NewCommentRequestSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use Vyuldashev\LaravelOpenApi\Factories\RequestBodyFactory;

class NewCommentRequestBody extends RequestBodyFactory
{
    public function build(): RequestBody
    {
        return RequestBody::create()
            ->description('Comment you want to create')
            ->content(
                MediaType::json()->schema(NewCommentRequestSchema::ref())
            );
    }
}
