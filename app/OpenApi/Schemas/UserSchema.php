<?php

namespace App\OpenApi\Schemas;

use GoldSpecDigital\ObjectOrientedOAS\Contracts\SchemaContract;
use GoldSpecDigital\ObjectOrientedOAS\Objects\AllOf;
use GoldSpecDigital\ObjectOrientedOAS\Objects\AnyOf;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Not;
use GoldSpecDigital\ObjectOrientedOAS\Objects\OneOf;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Contracts\Reusable;
use Vyuldashev\LaravelOpenApi\Factories\SchemaFactory;

class UserSchema extends SchemaFactory implements Reusable
{
    /**
     * @return AllOf|AnyOf|Not|OneOf|Schema
     */
    public function build(): SchemaContract
    {
        return Schema::object('User')
            ->required(
                'bio',
                'email',
                'image',
                'token',
                'username',
            )
            ->properties(
                Schema::string('username')->default(null),
                Schema::string('email')->default(null),
                Schema::string('bio')->default(null),
                Schema::string('image')->default(null),
                Schema::string('token')->default(null),
            );
    }
}
