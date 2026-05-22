<?php

namespace App\Filament\Rt\Resources\Wargas\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Validation\Rule;
use Filament\Schemas\Schema;

class WargaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Hidden::make("id_rt")->default(fn() => auth()->user()?->rt?->id),

            Tabs::make("Form Warga")
                ->tabs([
                    // TAB 1: DATA WARGA / KEPALA KELUARGA
                    Tab::make("Data Warga")
                        ->columns(2)
                        ->schema([
                            TextInput::make("nama_kepala_keluarga")
                                ->label("Nama Kepala Keluarga")
                                ->required()
                                ->maxLength(255),

                            TextInput::make("no_telepon")
                                ->label("No. Telepon")
                                ->tel()
                                ->required()
                                ->maxLength(20),

                            TextInput::make("alamat")
                                ->label("Alamat")
                                ->required()
                                ->maxLength(500)
                                ->columnSpanFull(), // Dibuat full karena biasanya alamat cukup panjang
                        ]),

                    // TAB 2: AKUN LOGIN WARGA
                    Tab::make("Akun Warga")
                        ->columns(2)
                        ->schema([
                            TextInput::make("account_name")
                                ->label("Username")
                                ->required()
                                ->maxLength(255),

                            TextInput::make("account_email")
                                ->label("Email")
                                ->email()
                                ->required()
                                ->maxLength(255)
                                ->rule(
                                    fn($record) => Rule::unique(
                                        "users",
                                        "email",
                                    )->ignore($record?->id_user),
                                )
                                ->validationMessages([
                                    "unique" =>
                                        "Email ini sudah digunakan oleh akun lain.",
                                ]),

                            TextInput::make("account_password")
                                ->label("Password")
                                ->password()
                                ->revealable()
                                ->required(
                                    fn(
                                        string $operation,
                                    ): bool => $operation === "create",
                                )
                                ->dehydrated(fn($state) => filled($state))
                                ->hint(
                                    fn(
                                        string $operation,
                                    ): ?string => $operation === "edit"
                                        ? "Kosongkan jika tidak ingin mengubah password"
                                        : null,
                                )
                                ->columnSpanFull(),
                        ]),
                ])
                ->columnSpanFull(),
        ]);
    }
}
