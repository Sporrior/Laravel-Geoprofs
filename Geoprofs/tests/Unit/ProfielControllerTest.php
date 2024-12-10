<?php

namespace Tests\Unit;

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

    public function test_show_profile()
    {
        $user = User::factory()->create();
        $adminRole = Role::factory()->create(['role_name' => 'admin']);
        $team = Team::factory()->create();

        $userInfo = UserInfo::factory()->create([
            'id' => $user->id,
            'role_id' => $adminRole->id,
            'team_id' => $team->id,
        ]);

        $werknemerRole = Role::firstOrCreate(['role_name' => 'werknemer']);
        $teamMember = UserInfo::factory()->create([
            'team_id' => $team->id,
            'role_id' => $werknemerRole->id,
        ]);

        $response = $this->actingAs($user)->get(route('profiel.show'));

        $response->assertStatus(200);
        $response->assertViewIs('profiel');
        $response->assertViewHas('user_info', $userInfo);
        $response->assertViewHas('users', function ($users) use ($teamMember) {
            return $users->contains($teamMember);
        });
    }

    public function test_update_profile_without_photo()
    {
        $user = User::factory()->create();
        $role = Role::factory()->create();
        $team = Team::factory()->create();

        $userInfo = UserInfo::factory()->create([
            'id' => $user->id,
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

    public function test_update_profile_with_photo()
    {
        // Mock the storage
        Storage::fake('public');

        $user = User::factory()->create();
        $role = Role::factory()->create();
        $team = Team::factory()->create();

        $userInfo = UserInfo::factory()->create([
            'id' => $user->id,
            'role_id' => $role->id,
            'team_id' => $team->id,
            'profielFoto' => 'profile_pictures/old_photo.jpg',
        ]);

        // Fake a new photo
        $newPhoto = UploadedFile::fake()->image('new_photo.jpg');

        $response = $this->actingAs($user)->put(route('profiel.update'), [
            'voornaam' => 'UpdatedFirstName',
            'achternaam' => 'UpdatedLastName',
            'email' => 'updated@example.com',
            'profielFoto' => $newPhoto,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Profiel succesvol bijgewerkt');

        // Get the expected stored photo path
        $storedPhotoPath = 'profile_pictures/' . $newPhoto->hashName();

        // Assert the new photo is stored correctly
        Storage::disk('public')->assertExists($storedPhotoPath);

        // Assert the old photo is removed
        Storage::disk('public')->assertMissing('profile_pictures/old_photo.jpg');

        // Assert the database updates
        $this->assertDatabaseHas('user_info', [
            'id' => $user->id,
            'voornaam' => 'UpdatedFirstName',
            'achternaam' => 'UpdatedLastName',
            'email' => 'updated@example.com',
            'profielFoto' => $storedPhotoPath,
        ]);
    }

    public function test_update_profile_with_duplicate_email()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $role = Role::factory()->create();
        $team = Team::factory()->create();

        $userInfo1 = UserInfo::factory()->create([
            'id' => $user1->id,
            'role_id' => $role->id,
            'team_id' => $team->id,
            'email' => 'existing@example.com',
        ]);

        $userInfo2 = UserInfo::factory()->create([
            'id' => $user2->id,
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