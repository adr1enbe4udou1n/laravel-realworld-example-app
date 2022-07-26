<?php

namespace App\OpenApi\Responses;

use App\OpenApi\Schemas\UserResponseSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use Vyuldashev\LaravelOpenApi\Contracts\Reusable;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;

class UserResponse extends ResponseFactory implements Reusable
{
    public function build(): Response
    {
        return Response::ok()
            ->description('Success')
            ->content(MediaType::json()->schema(UserResponseSchema::ref()));
    }
}
