<?php

namespace App\Filament\Blocks;

use App\Forms\Components\Block;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class ImageBlock extends Block
{
    public string $view = "blocks.image";
    public string $name = "image-block";

    public static function fields()
    {
        return [
            TextInput::make("title")->default("Hello"),
            FileUpload::make("image")
                ->image()
                ->imageEditorAspectRatios(["16:9", "4:3", "1:1"])
                ->imageEditor(),
        ];
    }

    // public function getImageAttribute($value)
    // {
    //     return gettype($value);
    // }
}
