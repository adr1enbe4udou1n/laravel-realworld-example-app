<?php

namespace App\OpenApi\Responses;

use App\OpenApi\Schemas\MultipleArticlesResponseSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;

class MultipleArticlesResponse extends ResponseFactory
{
    public function build(): Response
    {
        return Response::ok()
            ->description('Success')
            ->content(MediaType::json()->schema(MultipleArticlesResponseSchema::ref()))
        ;
    }
}
