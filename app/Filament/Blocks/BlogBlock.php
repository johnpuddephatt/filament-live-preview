<?php

namespace App\Filament\Blocks;

use App\Forms\Components\Block;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class BlogBlock extends Block
{
    public string $view = "blocks.blog";
    public string $name = "blog-block";

    // public $casts = [
    //     "number_of_posts" => "number",
    // ];

    public static function fields()
    {
        return [
            TextInput::make("title")->default("Welcome to the blog"),
            TextInput::make("subtitle")->default(
                "Learn how to grow your business with our expert advice."
            ),
            Select::make("number_of_posts")
                ->options([3 => "One row", 6 => "Two rows"])

                ->default(3),
        ];
    }

    public function getPostsAttribute($value)
    {
        return \App\Models\Blog\Post::take(
            $this->__get("number_of_posts")
        )->get();
    }
}
