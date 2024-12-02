<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\VerlofAanvragen;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ZiekmeldenIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_sick_leave_request()
    {
        // Arrange: Create and authenticate a user with all necessary fields
        $user = User::factory()->create([
            'voornaam' => 'Jane',
            'achternaam' => 'Doe',
            'email' => 'jane@example.com',
            'verlof_dagen' => 10,
            'telefoon' => '0612345678',
            'password' => Hash::make('password123'),
        ]);

        $this->actingAs($user);

        // Act: Send a POST request to create a sick leave request
        $response = $this->post(route('ziekmelden.store'), [
            'verlof_reden' => 'Flu',
        ]);

        // Assert: Check that the sick leave request was created successfully in the database
        $this->assertDatabaseHas('verlofaanvragens', [
            'verlof_reden' => 'Flu',
            'user_id' => $user->id,
            'status' => 1,
        ]);

        // Assert: Check for the redirect and the session message
        $response->assertRedirect(route('ziekmelden.index'));
        $response->assertSessionHas('success', 'Ziekmelding succesvol ingediend.');
    }

    public function test_validation_fails_without_reason_for_sick_leave_request()
    {
        // Arrange: Create and authenticate a user
        $user = User::factory()->create([
            'voornaam' => 'John',
            'achternaam' => 'Smith',
            'email' => 'john@example.com',
            'verlof_dagen' => 15,
            'telefoon' => '0698765432',
            'password' => Hash::make('password123'),
        ]);

        $this->actingAs($user);

        // Act: Attempt to create a sick leave request without a reason
        $response = $this->post(route('ziekmelden.store'), [
            // 'verlof_reden' is missing
        ]);

        // Assert: Check that the validation fails
        $response->assertSessionHasErrors(['verlof_reden']);
        $this->assertDatabaseMissing('verlofaanvragens', ['user_id' => $user->id]);
    }
}
