<?php

namespace App\OpenApi\Parameters;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Factories\ParametersFactory;

class ListFeedParameters extends ParametersFactory
{
    /**
     * @return Parameter[]
     */
    public function build(): array
    {
        return [

            Parameter::query()
                ->name('limit')
                ->description('Limit number of articles returned (default is 20)')
                ->required(false)
                ->schema(Schema::integer()),

            Parameter::query()
                ->name('offset')
                ->description('Offset/skip number of articles (default is 0)')
                ->required(false)
                ->schema(Schema::integer()),

        ];
    }
}
