<?php

namespace Tests\Unit\wessam;

use App\Models\User;
use App\Models\UserInfo;
use App\Models\Role;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ProfielControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_show_profile()
    {
        $user = User::factory()->create();
        $role = Role::factory()->create(['role_name' => 'admin']);
        $team = Team::factory()->create();

        $userInfo = UserInfo::factory()->create([
            'id' => $user->id,
            'voornaam' => 'John',
            'achternaam' => 'Doe',
            'email' => 'john@example.com',
            'telefoon' => '0612345678',
            'role_id' => $role->id,
            'team_id' => $team->id,
        ]);

        $response = $this->actingAs($user)->get(route('profiel.show'));

        $response->assertStatus(200);
        $response->assertViewIs('profiel');
        $response->assertViewHas('user_info', $userInfo);
    }

    /** @test */
    public function test_update_profile_without_photo()
    {
        $user = User::factory()->create();
        $role = Role::factory()->create();
        $team = Team::factory()->create();

        $userInfo = UserInfo::factory()->create([
            'id' => $user->id,
            'voornaam' => 'John',
            'achternaam' => 'Doe',
            'email' => 'john@example.com',
            'telefoon' => '0612345678',
            'role_id' => $role->id,
            'team_id' => $team->id,
        ]);

        $response = $this->actingAs($user)->put(route('profiel.update'), [
            'voornaam' => 'UpdatedFirstName',
            'achternaam' => 'UpdatedLastName',
            'email' => 'updated@example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Profiel succesvol bijgewerkt');

        $this->assertDatabaseHas('user_info', [
            'id' => $user->id,
            'voornaam' => 'UpdatedFirstName',
            'achternaam' => 'UpdatedLastName',
            'email' => 'updated@example.com',
        ]);
    }

    /** @test */
    public function test_update_profile_with_photo()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $role = Role::factory()->create();
        $team = Team::factory()->create();

        $userInfo = UserInfo::factory()->create([
            'id' => $user->id,
            'voornaam' => 'John',
            'achternaam' => 'Doe',
            'email' => 'john@example.com',
            'telefoon' => '0612345678',
            'profielFoto' => 'profile_pictures/default_profile_photo.png', // default_profile_photo.png the default photo. path in DB: profile_pictures/default_profile_photo.png
            'role_id' => $role->id,
            'team_id' => $team->id,
        ]);

        $newPhoto = UploadedFile::fake()->image('new_photo.png');

        $response = $this->actingAs($user)->put(route('profiel.update'), [
            'voornaam' => 'UpdatedFirstName',
            'achternaam' => 'UpdatedLastName',
            'email' => 'updated@example.com',
            'profielFoto' => $newPhoto,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Profiel succesvol bijgewerkt');

        $storedPhotoPath = 'profile_pictures/' . $newPhoto->hashName();

        Storage::disk('public')->assertExists($storedPhotoPath);
        Storage::disk('public')->assertMissing('profile_pictures/old_photo.jpg');

        $this->assertDatabaseHas('user_info', [
            'id' => $user->id,
            'voornaam' => 'UpdatedFirstName',
            'achternaam' => 'UpdatedLastName',
            'email' => 'updated@example.com',
            'profielFoto' => $storedPhotoPath,
        ]);
    }

    /** @test */
    public function test_update_profile_with_duplicate_email()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $role = Role::factory()->create();
        $team = Team::factory()->create();

        UserInfo::factory()->create([
            'id' => $user1->id,
            'voornaam' => 'John',
            'achternaam' => 'Doe',
            'email' => 'existing@example.com',
            'telefoon' => '0612345678',
            'role_id' => $role->id,
            'team_id' => $team->id,
        ]);

        UserInfo::factory()->create([
            'id' => $user2->id,
            'voornaam' => 'Jane',
            'achternaam' => 'Smith',
            'email' => 'unique@example.com',
            'telefoon' => '0612345678',
            'role_id' => $role->id,
            'team_id' => $team->id,
        ]);

        $response = $this->actingAs($user2)->put(route('profiel.update'), [
            'voornaam' => 'UpdatedFirstName',
            'achternaam' => 'UpdatedLastName',
            'email' => 'existing@example.com',
        ]);

        $response->assertSessionHasErrors(['email']);
    }
}