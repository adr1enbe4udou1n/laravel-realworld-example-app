<?php

namespace App\OpenApi\Responses;

use App\OpenApi\Schemas\SingleArticleResponseSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;

class SingleArticleResponse extends ResponseFactory
{
    public function build(): Response
    {
        return Response::ok()
            ->description('Success')
            ->content(MediaType::json()->schema(SingleArticleResponseSchema::ref()))
        ;
    }
}
