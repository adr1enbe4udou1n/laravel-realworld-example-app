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

class NewArticleSchema extends SchemaFactory implements Reusable
{
    /**
     * @return AllOf|AnyOf|Not|OneOf|Schema
     */
    public function build(): SchemaContract
    {
        return Schema::object('NewArticle')
            ->required(
                'body',
                'description',
                'title',
            )
            ->properties(
                Schema::string('title')->default(null),
                Schema::string('description')->default(null),
                Schema::string('body')->default(null),
                Schema::array('tagList')->items(Schema::string())->default(null),
            );
    }
}
