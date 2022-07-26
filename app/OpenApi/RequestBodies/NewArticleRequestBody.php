<?php

namespace App\OpenApi\RequestBodies;

use App\OpenApi\Schemas\NewArticleRequestSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use Vyuldashev\LaravelOpenApi\Factories\RequestBodyFactory;

class NewArticleRequestBody extends RequestBodyFactory
{
    public function build(): RequestBody
    {
        return RequestBody::create()
            ->description('Article to create')
            ->content(
                MediaType::json()->schema(NewArticleRequestSchema::ref())
            );
    }
}
