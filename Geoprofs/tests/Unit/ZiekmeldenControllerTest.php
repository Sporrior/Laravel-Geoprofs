<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\VerlofAanvragen; // Make sure this points to your model
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ZiekmeldenControllerTest extends TestCase
{
    use RefreshDatabase; // Use this to reset the database after each test

    public function test_store_creates_ziekmelding()
    {
        // Arrange: Create a user and authenticate
        $user = User::factory()->create([
            'voornaam' => 'John',
            'achternaam' => 'Doe',
            'email' => 'john@example.com',
            'verlof_dagen' => 25,
            'telefoon' => '1234567890',
            'password' => Hash::make('password123'),
        ]);

        $this->actingAs($user);

        // Act: Send a POST request to store a ziekmelding
        $response = $this->post(route('ziekmelden.store'), [
            'verlof_reden' => 'Flu',
        ]);

        // Assert: Check if the ziekmelding was created in the database
        $this->assertDatabaseHas('verlofaanvragens', [
            'verlof_reden' => 'Flu',
            'user_id' => $user->id,
        ]);

        // Assert: Check the response status
        $response->assertRedirect(route('ziekmelden.index'));
        $response->assertSessionHas('success', 'Ziekmelding succesvol ingediend.');
    }

    public function test_store_fails_without_reason()
    {
        // Arrange: Create a user with all required fields and authenticate
        $user = User::factory()->create([
            'voornaam' => 'Jane',  
            'achternaam' => 'Smith',
            'email' => 'jane@example.com',
            'verlof_dagen' => 15,
            'telefoon' => '0987654321',
            'password' => Hash::make('password123'),
        ]);

        $this->actingAs($user);

        // Act: Send a POST request to store a ziekmelding without 'verlof_reden'
        $response = $this->post(route('ziekmelden.store'), [
            // 'verlof_reden' is missing
        ]);

        // Assert: Check if validation fails
        $response->assertSessionHasErrors(['verlof_reden']);
    }
}

