<?php

namespace App\Forms\Trov\Blocks;

use App\Forms\Trov\Blocks\Code;
use App\Forms\Trov\Blocks\Grid;
use App\Forms\Trov\Blocks\Image;
use App\Forms\Trov\Blocks\Details;
use App\Forms\Trov\Blocks\RichText;
use App\Forms\Trov\Blocks\ImageLeft;
use App\Forms\Trov\Blocks\ImageRight;
use Filament\Forms\Components\Builder;
use FilamentTiptapEditor\TiptapEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Builder\Block;
use FilamentCurator\Forms\Components\MediaPicker;

class Details
{
    public static function make(): Block
    {
        return Block::make('details')
            ->label('Details')
            ->schema([
                TextInput::make('summary')
                    ->required(),
                Builder::make('content')
                    ->createItemButtonLabel('Add Content')
                    ->blocks([
                        RichText::make('simple'),
                        Grid::make(),
                        Image::make(),
                        ImageLeft::make(),
                        ImageRight::make(),
                        Code::make(),
                    ])
            ]);
    }
}
