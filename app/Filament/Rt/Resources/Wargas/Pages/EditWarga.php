<?php

namespace App\Filament\Rt\Resources\Wargas\Pages;

use App\Filament\Rt\Resources\Wargas\WargaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;

class EditWarga extends EditRecord
{
    protected static string $resource = WargaResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $user = $this->record->user;

        $data["account_name"] = $user?->name;
        $data["account_email"] = $user?->email;
        // account_password dibiarkan kosong saat edit

        return $data;
    }

    /**
     * Update data User, lalu hapus field virtual sebelum menyimpan RT.
     * Password hanya diubah jika diisi.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $user = $this->record->user;

        if ($user) {
            $updateData = [
                "name" => $data["account_name"],
                "email" => $data["account_email"],
            ];

            if (
                !empty($data["account_password"]) &&
                filled($data["account_password"])
            ) {
                $updateData["password"] = Hash::make($data["account_password"]);
            }

            $user->update($updateData);
        }

        unset(
            $data["account_name"],
            $data["account_email"],
            $data["account_password"],
        );

        return $data;
    }
}
