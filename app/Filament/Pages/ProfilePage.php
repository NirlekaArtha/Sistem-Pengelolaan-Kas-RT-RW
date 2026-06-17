<?php

namespace App\Filament\Pages;

use App\Models\RT;
use App\Models\RW;
use App\Models\User;
use App\Models\Warga;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use BackedEnum;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;

class ProfilePage extends Page
{
    use WithFileUploads;

    protected static string|BackedEnum|null $navigationIcon = "heroicon-o-user-circle";

    protected static ?string $navigationLabel = "Profil Saya";

    protected static ?string $title = "Profil Saya";

    protected static ?int $navigationSort = 99;

    protected string $view = "filament.pages.profile-page";

    public bool $isEditing = false;

    public string $name = "";

    public string $email = "";

    public ?string $noTelepon = null;

    public ?string $profilePicturePath = null;

    public mixed $profilePicture = null;

    public function mount(): void
    {
        $this->fillFromUser();
    }

    public function enableEdit(): void
    {
        $this->isEditing = true;
    }

    public function cancelEdit(): void
    {
        $this->isEditing = false;
        $this->fillFromUser();
    }

    public function save(): void
    {
        abort_unless(auth()->check(), 403);

        $user = auth()->user();

        $validated = $this->validate([
            "name" => ["required", "string", "max:255"],
            "email" => [
                "required",
                "string",
                "email",
                "max:255",
                Rule::unique("users", "email")->ignore($user->id),
            ],
            "noTelepon" => ["nullable", "string", "max:20"],
            "profilePicture" => ["nullable", "image", "max:2048"],
        ]);

        $profilePicturePath = $this->profilePicture?->storePublicly(
            "profile-pictures",
            "public",
        );

        $user->update([
            "name" => $validated["name"],
            "email" => $validated["email"],
            "profile_picture" => $profilePicturePath ?? $user->profile_picture,
        ]);

        $profileRecord = $user->profileRecord();

        if ($profileRecord !== null) {
            $profileRecord->update([
                "no_telepon" => $validated["noTelepon"],
            ]);
        }

        $this->isEditing = false;
        $this->profilePicture = null;
        $this->fillFromUser();

        Notification::make()
            ->title("Profile updated successfully")
            ->success()
            ->send();
    }

    public function profileRecord(): RW|RT|Warga|null
    {
        return auth()->user()?->profileRecord();
    }

    protected function fillFromUser(): void
    {
        $user = auth()->user();

        if (!$user instanceof User) {
            return;
        }

        $user->loadMissing(["rw", "rt", "warga"]);

        $this->name = $user->name;
        $this->email = $user->email;
        $this->noTelepon = $user->profileRecord()?->no_telepon;
        $this->profilePicturePath = $user->profile_picture;
        $this->profilePicture = null;
    }

    public function profileImageUrl(): string
    {
        if (
            $this->profilePicture instanceof
            \Livewire\Features\SupportFileUploads\TemporaryUploadedFile
        ) {
            return $this->profilePicture->temporaryUrl();
        }

        if ($this->profilePicturePath) {
            return Storage::url($this->profilePicturePath);
        }

        return "https://ui-avatars.com/api/?name=" .
            urlencode($this->name ?: "User");
    }
}
