<?php

namespace App\Filament\Rw\Resources\RTS\Pages;

use App\Enums\UserRole;
use App\Filament\Rw\Resources\RTS\RTResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateRT extends CreateRecord
{
    protected static string $resource = RTResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Buat akun User baru untuk RT
        $user = User::create([
            'name' => $data['account_name'],
            'email' => $data['account_email'],
            'password' => Hash::make($data['account_password']),
            'role' => UserRole::RT,
        ]);

        $data['id_user'] = $user->id;

        // Hapus field virtual agar tidak masuk ke tabel RT
        unset($data['account_name'], $data['account_email'], $data['account_password']);

        return $data;
    }
}
