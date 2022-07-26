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

class SingleCommentResponseSchema extends SchemaFactory implements Reusable
{
    /**
     * @return AllOf|AnyOf|Not|OneOf|Schema
     */
    public function build(): SchemaContract
    {
        return Schema::object('SingleCommentResponse')
            ->required(
                'comment',
            )
            ->properties(
                CommentSchema::ref('comment')
            );
    }
}
