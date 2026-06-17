@php
    use Illuminate\Support\Facades\Storage;

    $user = auth()?->user();
    $profileRecord = $this?->profileRecord();
@endphp

<x-filament-panels::page>
    @if (! $this?->isEditing)
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col gap-6 md:flex-row md:items-center">
                <img
                    src="{{ $this?->profileImageUrl() }}"
                    alt="Foto profil"
                    class="h-24 w-24 rounded-full object-cover ring-4 ring-primary-500/20"
                />

                <div class="space-y-2">
                    <h2 class="text-2xl font-semibold text-gray-950 dark:text-white">
                        {{ $user?->name }}
                    </h2>
                    <div class="inline-flex rounded-full bg-primary-50 px-3 py-1 text-sm font-medium text-primary-700 dark:bg-primary-500/10 dark:text-primary-300">
                        {{ $user?->role }}
                    </div>
                </div>

                <div class="md:ml-auto flex gap-3">
                    <x-filament::button wire:click="enableEdit" icon="heroicon-o-pencil-square">
                        Edit Profile
                    </x-filament::button>
                    <form action="{{ filament()?->getLogoutUrl() }}" method="post">
                        @csrf
                        <x-filament::button type="submit" color="danger" icon="heroicon-o-arrow-left-on-rectangle">
                            Logout
                        </x-filament::button>
                    </form>
                </div>
            </div>
        </div>

        <x-filament::section heading="Informasi Akun">
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <div class="text-sm font-medium text-gray-500">Nama Lengkap</div>
                    <div class="mt-1 text-sm text-gray-950 dark:text-white">{{ $user?->name }}</div>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-500">Email</div>
                    <div class="mt-1 text-sm text-gray-950 dark:text-white">{{ $user?->email }}</div>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-500">Nomor Telepon</div>
                    <div class="mt-1 text-sm text-gray-950 dark:text-white">{{ $profileRecord?->no_telepon ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-500">Role</div>
                    <div class="mt-1 text-sm text-gray-950 dark:text-white">{{ $user?->role }}</div>
                </div>
            </div>
        </x-filament::section>

        @if ($profileRecord)
            @if ($user?->role === 'RW')
                <x-filament::section heading="Informasi Anda">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <div class="text-sm font-medium text-gray-500">Nomor RW</div>
                            <div class="mt-1 text-sm text-gray-950 dark:text-white">{{ $profileRecord?->nomor_rw }}</div>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-500">Alamat</div>
                            <div class="mt-1 text-sm text-gray-950 dark:text-white">{{ $profileRecord?->alamat }}</div>
                        </div>
                    </div>
                </x-filament::section>
            @endif

            @if ($user?->role === 'RT')
                <x-filament::section heading="Informasi Anda">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <div class="text-sm font-medium text-gray-500">Nomor RT</div>
                            <div class="mt-1 text-sm text-gray-950 dark:text-white">{{ $profileRecord?->nomor_rt }}</div>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-500">Alamat</div>
                            <div class="mt-1 text-sm text-gray-950 dark:text-white">{{ $profileRecord?->alamat }}</div>
                        </div>
                    </div>
                </x-filament::section>
                <x-filament::section heading="Informasi RW">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <div class="text-sm font-medium text-gray-500">Nama RW</div>
                            <div class="mt-1 text-sm text-gray-950 dark:text-white">{{ $profileRecord?->rw?->nama ?? "-" }}</div>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-500">Nomor RW</div>
                            <div class="mt-1 text-sm text-gray-950 dark:text-white">{{ $profileRecord?->rw?->nomor_rw ?? "-" }}</div>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-500">Alamat</div>
                            <div class="mt-1 text-sm text-gray-950 dark:text-white">{{ $profileRecord?->rw?->alamat ?? "-"  }}</div>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-500">No. Telepon</div>
                            <div class="mt-1 text-sm text-gray-950 dark:text-white">{{ $profileRecord?->rw?->no_telepon ?? "-"  }}</div>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-500">Email</div>
                            <div class="mt-1 text-sm text-gray-950 dark:text-white">{{ $profileRecord?->rw?->user?->email ?? "-"  }}</div>
                        </div>
                    </div>
                </x-filament::section>
            @endif

            @if ($user?->role === 'Warga')
                <x-filament::section heading="Informasi Anda">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <div class="text-sm font-medium text-gray-500">Nama Kepala Keluarga</div>
                            <div class="mt-1 text-sm text-gray-950 dark:text-white">{{ $profileRecord?->nama_kepala_keluarga }}</div>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-500">RT/RW</div>
                            <div class="mt-1 text-sm text-gray-950 dark:text-white">{{ $profileRecord?->rt?->nomor_rt ?? '—' }}/{{ $profileRecord?->rt?->rw?->nomor_rw ?? '—' }}</div>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-500">Alamat</div>
                            <div class="mt-1 text-sm text-gray-950 dark:text-white">{{ $profileRecord?->alamat }}</div>
                        </div>
                    </div>
                </x-filament::section>
                <x-filament::section heading="Informasi RT">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <div class="text-sm font-medium text-gray-500">Nomor RT</div>
                            <div class="mt-1 text-sm text-gray-950 dark:text-white">{{ $profileRecord?->rt?->nomor_rt ?? "-" }}</div>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-500">Alamat</div>
                            <div class="mt-1 text-sm text-gray-950 dark:text-white">{{ $profileRecord?->rt?->alamat }}</div>
                        </div>
                    </div>
                </x-filament::section>
                <x-filament::section heading="Informasi RW">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <div class="text-sm font-medium text-gray-500">Nama RW</div>
                            <div class="mt-1 text-sm text-gray-950 dark:text-white">{{ $profileRecord?->rt?->rw?->nama ?? "-" }}</div>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-500">Nomor RW</div>
                            <div class="mt-1 text-sm text-gray-950 dark:text-white">{{ $profileRecord?->rt?->rw?->nomor_rw ?? "-" }}</div>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-500">Alamat</div>
                            <div class="mt-1 text-sm text-gray-950 dark:text-white">{{ $profileRecord?->rt?->rw?->alamat ?? "-"  }}</div>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-500">No. Telepon</div>
                            <div class="mt-1 text-sm text-gray-950 dark:text-white">{{ $profileRecord?->rt?->rw?->no_telepon ?? "-"  }}</div>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-500">Email</div>
                            <div class="mt-1 text-sm text-gray-950 dark:text-white">{{ $profileRecord?->rt?->rw?->user?->email ?? "-"  }}</div>
                        </div>
                    </div>
                </x-filament::section>
            @endif
        @endif
    @else
        <div class="space-y-6">
            <x-filament::section heading="Edit Profile">
                <div class="grid gap-6 md:grid-cols-2">
                    <div class="md:col-span-2 flex items-center gap-4">
                        <img
                            src="{{ $this?->profileImageUrl() }}"
                            alt="Preview foto profil"
                            class="h-20 w-20 rounded-full object-cover ring-4 ring-primary-500/20"
                        />
                        <div>
                            <div class="text-sm font-medium text-gray-500">Foto profil</div>
                            <input
                                type="file"
                                wire:model="profilePicture"
                                accept="image/*"
                                class="mt-2 block w-full text-sm text-gray-700 file:mr-4 file:rounded-lg file:border-0 file:bg-primary-600 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-primary-500 dark:text-gray-300"
                            />
                            @error('profilePicture')
                                <div class="mt-2 text-sm text-danger-600">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Nama Lengkap</label>
                        <input
                            type="text"
                            wire:model="name"
                            class="mt-2 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm outline-none focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-950 dark:text-white"
                        />
                        @error('name')
                            <div class="mt-2 text-sm text-danger-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Email Alamat</label>
                        <input
                            type="email"
                            wire:model="email"
                            class="mt-2 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm outline-none focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-950 dark:text-white"
                        />
                        @error('email')
                            <div class="mt-2 text-sm text-danger-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Nomor Telepon</label>
                        <input
                            type="tel"
                            wire:model="noTelepon"
                            class="mt-2 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm outline-none focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-950 dark:text-white"
                        />
                        @error('noTelepon')
                            <div class="mt-2 text-sm text-danger-600">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </x-filament::section>

            <div class="flex gap-3">
                <x-filament::button wire:click="save" icon="heroicon-o-check">
                    Simpan Perubahan
                </x-filament::button>
                <x-filament::button wire:click="cancelEdit" color="gray" icon="heroicon-o-x-mark">
                    Kembali
                </x-filament::button>
            </div>
        </div>
    @endif
</x-filament-panels::page>
