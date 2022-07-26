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

class CommentSchema extends SchemaFactory implements Reusable
{
    /**
     * @return AllOf|AnyOf|Not|OneOf|Schema
     */
    public function build(): SchemaContract
    {
        return Schema::object('Comment')
            ->required(
                'author',
                'body',
                'createdAt',
                'id',
                'updatedAt'
            )
            ->properties(
                Schema::integer('id'),
                Schema::string('body'),
                Schema::string('createdAt')->format(Schema::FORMAT_DATE_TIME),
                Schema::string('updatedAt')->format(Schema::FORMAT_DATE_TIME),
                ProfileSchema::ref('author'),
            );
    }
}
