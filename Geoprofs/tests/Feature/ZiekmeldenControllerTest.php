<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserInfo;
use App\Models\VerlofAanvragen;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ZiekmeldenControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_the_ziekmelden_page()
    {
        // Arrange: Create a user and associate UserInfo
        $user = User::factory()->create();
        UserInfo::factory()->create(['id' => $user->id]);

        // Act: Authenticate and make a GET request to the index route
        $response = $this->actingAs($user)->get(route('ziekmelden.index'));

        // Assert: Check the response
        $response->assertStatus(200);
        $response->assertViewIs('ziekmelden');
        $response->assertViewHas('user_info');
    }

    /** @test */
    public function it_allows_the_user_to_store_a_ziekmelding()
    {
        // Arrange: Create a user
        $user = User::factory()->create();

        // Act: Authenticate and make a POST request to store a ziekmelding
        $response = $this->actingAs($user)->post(route('ziekmelden.store'), [
            'verlof_reden' => 'Ik ben ziek',
        ]);

        // Assert: Verify redirection and database state
        $response->assertRedirect(route('ziekmelden.index'));
        $response->assertSessionHas('success', 'Ziekmelding succesvol ingediend.');

        $this->assertDatabaseHas('verlofaanvragen', [
            'verlof_reden' => 'Ik ben ziek',
            'user_id' => $user->id,
            'verlof_soort' => 1,
            'status' => 1,
        ]);
    }

    /** @test */
    public function it_validates_the_store_request()
    {
        // Arrange: Create a user
        $user = User::factory()->create();

        // Act: Authenticate and make a POST request with invalid data
        $response = $this->actingAs($user)->post(route('ziekmelden.store'), []);

        // Assert: Verify validation errors
        $response->assertSessionHasErrors(['verlof_reden']);
        $this->assertDatabaseCount('verlofaanvragen', 0);
    }
}
