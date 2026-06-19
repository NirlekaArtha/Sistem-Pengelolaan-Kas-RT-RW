<?php

namespace App\Filament\Rt\Resources\Wargas\Pages;

use App\Enums\UserRole;
use App\Filament\Rt\Resources\Wargas\WargaResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateWarga extends CreateRecord
{
    protected static string $resource = WargaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = User::create([
            'name' => $data['account_name'],
            'email' => $data['account_email'],
            'password' => Hash::make($data['account_password']),
            'role' => UserRole::WARGA,
        ]);

        $data['id_user'] = $user->id;

        unset(
            $data['account_name'],
            $data['account_email'],
            $data['account_password'],
        );

        return $data;
    }
}
