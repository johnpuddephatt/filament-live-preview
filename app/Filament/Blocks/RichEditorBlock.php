<?php

namespace App\Filament\Blocks;

use App\Forms\Components\Block;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\RichEditor;

class RichEditorBlock extends Block
{
    public string $view = "blocks.rich-editor";
    public string $name = "rich-editor-block";

    public static function fields()
    {
        return [
            TextInput::make("title")->default("Hello"),
            RichEditor::make("content"),
        ];
    }
}
