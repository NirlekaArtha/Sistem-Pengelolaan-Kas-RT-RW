<?php

namespace Tests\Feature;

use App\Models\RT;
use App\Models\RW;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfilePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_renders_for_all_roles(): void
    {
        $rwUser = User::factory()->create([
            'name' => 'RW User',
            'email' => 'rw@example.com',
            'role' => 'RW',
        ]);

        $rw = RW::create([
            'id_user' => $rwUser->id,
            'nomor_rw' => '001',
            'nama' => 'RW User',
            'alamat' => 'RW Address',
            'no_telepon' => '080000000001',
        ]);

        $rtUser = User::factory()->create([
            'name' => 'RT User',
            'email' => 'rt@example.com',
            'role' => 'RT',
        ]);

        $rt = RT::create([
            'id_rw' => $rw->id,
            'id_user' => $rtUser->id,
            'nomor_rt' => '002',
            'nama' => 'RT User',
            'alamat' => 'RT Address',
            'no_telepon' => '080000000002',
        ]);

        $wargaUser = User::factory()->create([
            'name' => 'Warga User',
            'email' => 'warga@example.com',
            'role' => 'Warga',
        ]);

        Warga::create([
            'id_rt' => $rt->id,
            'id_user' => $wargaUser->id,
            'nama_kepala_keluarga' => 'Warga User',
            'alamat' => 'Warga Address',
            'no_telepon' => '080000000003',
        ]);

        $this->actingAs($rwUser)
            ->get('/rw/profile-page')
            ->assertOk()
            ->assertSee('My Profile')
            ->assertSee('RW Information');

        $this->actingAs($rtUser)
            ->get('/rt/profile-page')
            ->assertOk()
            ->assertSee('My Profile')
            ->assertSee('RT Information');

        $this->actingAs($wargaUser)
            ->get('/warga/profile-page')
            ->assertOk()
            ->assertSee('My Profile')
            ->assertSee('Resident Information');
    }
}
