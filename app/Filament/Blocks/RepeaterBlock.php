<?php

namespace App\Filament\Blocks;

use App\Forms\Components\Block;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class RepeaterBlock extends Block
{
    public string $view = "blocks.repeater";
    public string $name = "repeater-block";

    public $casts = [
        "stats" => "object",
    ];

    public static function fields()
    {
        return [
            TextInput::make("title"),
            TextInput::make("description"),
            Repeater::make("stats")->schema([
                TextInput::make("title"),
                TextInput::make("description"),
            ]),
        ];
    }
}
