<?php

namespace App\Filament\Blocks;

use App\Forms\Components\Block;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class TestBlock extends Block
{
    public string $view = "blocks.test";
    public string $name = "test-block";

    public static function fields()
    {
        return [
            TextInput::make("title")
                ->default("Data to enrich your online business")
                ->hint("Converted to title case."),
            Toggle::make("show"),
        ];
    }

    public function getTitleAttribute($value): string
    {
        return ucwords($value);
    }
}
