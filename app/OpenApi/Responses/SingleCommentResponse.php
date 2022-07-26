<?php

namespace App\OpenApi\Responses;

use App\OpenApi\Schemas\SingleCommentResponseSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;

class SingleCommentResponse extends ResponseFactory
{
    public function build(): Response
    {
        return Response::ok()
            ->description('Success')
            ->content(MediaType::json()->schema(SingleCommentResponseSchema::ref()));
    }
}
