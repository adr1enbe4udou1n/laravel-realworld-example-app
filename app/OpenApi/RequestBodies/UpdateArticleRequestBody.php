<?php

namespace App\OpenApi\RequestBodies;

use App\OpenApi\Schemas\UpdateArticleRequestSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use Vyuldashev\LaravelOpenApi\Factories\RequestBodyFactory;

class UpdateArticleRequestBody extends RequestBodyFactory
{
    public function build(): RequestBody
    {
        return RequestBody::create()
            ->description('Article to update')
            ->content(
                MediaType::json()->schema(UpdateArticleRequestSchema::ref())
            );
    }
}
