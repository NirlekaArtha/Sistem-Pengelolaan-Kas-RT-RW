<?php

namespace App\Filament\Rw\Resources\RTS\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rule;

class RTForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('id_rw')
                    ->default(fn () => auth()->user()?->rw?->id),

                Tabs::make('Form RT')
                    ->tabs([
                        Tab::make('Data RT')
                            ->columns(2)
                            ->schema([
                                TextInput::make('nomor_rt')
                                    ->label('Nomor RT')
                                    ->required()
                                    ->maxLength(10)
                                    ->unique(
                                        table: 'r_t_s',
                                        column: 'nomor_rt',
                                        ignoreRecord: true,
                                        modifyRuleUsing: function ($rule) {
                                            return $rule->where('id_rw', auth()->user()?->rw?->id);
                                        }
                                    )
                                    ->validationMessages([
                                        'unique' => 'Nomor RT ini sudah digunakan.',
                                    ]),

                                TextInput::make('nama')
                                    ->label('Nama RT')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('alamat')
                                    ->label('Alamat')
                                    ->required()
                                    ->maxLength(500),

                                TextInput::make('no_telepon')
                                    ->label('No. Telepon')
                                    ->tel()
                                    ->required()
                                    ->maxLength(20),
                            ]),

                        Tab::make('Akun')
                            ->columns(2)
                            ->schema([
                                TextInput::make('account_name')
                                    ->label('Username')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('account_email')
                                    ->label('Email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255)
                                    ->rule(fn ($record) =>
                                        Rule::unique('users', 'email')
                                            ->ignore($record?->id_user)
                                    )
                                    ->validationMessages([
                                        'unique' => 'Email ini sudah digunakan oleh akun lain.',
                                    ]),

                                TextInput::make('account_password')
                                    ->label('Password')
                                    ->password()
                                    ->revealable()
                                    ->required(fn (string $operation): bool => $operation === 'create')
                                    ->dehydrated(fn ($state) => filled($state))
                                    ->hint(fn (string $operation): ?string => $operation === 'edit' ? 'Kosongkan jika tidak ingin mengubah password' : null)
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
