<?php

namespace App\Filament\Rt\Resources\JenisIuranWargas\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class JenisIuranWargaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make("Jenis Iuran")
                ->description("Form input jenis iuran untuk RT")
                ->icon("heroicon-o-currency-dollar")
                ->columns(2)
                ->schema([
                    TextInput::make("jenis_iuran")->required(),
                    TextInput::make("jumlah")->required()->numeric(),
                ]),
        ]);
    }
}
