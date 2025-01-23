<?php

namespace Tests\Feature\ahmad;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\UserInfo;
use App\Models\Type;
use App\Models\VerlofAanvragen;

class KeuringControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test index method retrieves filtered data correctly.
     */
    public function testIndexMethodFiltersDataCorrectly()
    {
        // Seed database with test data
        $type1 = Type::factory()->create();
        $type2 = Type::factory()->create();

        $user = User::factory()->create();
        $userInfo = UserInfo::factory()->create(['id' => $user->id]);

        VerlofAanvragen::factory()->create([
            'verlof_soort' => $type1->id,
            'user_id' => $userInfo->id,
        ]);

        VerlofAanvragen::factory()->create([
            'verlof_soort' => $type2->id,
            'user_id' => $userInfo->id,
        ]);

        $this->actingAs($user);

        $response = $this->get(route('keuring.index', ['types' => [$type1->id]]));

        $response->assertStatus(200);
        $response->assertViewHas('verlofaanvragen', function ($verlofaanvragen) use ($type1) {
            return $verlofaanvragen->pluck('verlof_soort')->contains($type1->id);
        });
    }

    /**
     * Test updating status of a leave request.
     */
    public function testUpdateStatusMethod()
{
    $user = User::factory()->create();
    $userInfo = UserInfo::factory()->create(['id' => $user->id, 'verlof_dagen' => 10]);

    $verlofAanvraag = VerlofAanvragen::factory()->create([
        'user_id' => $userInfo->id,
        'status' => 0, // Initial status
        'verlof_soort' => 2, // Leave that adjusts days
        'start_datum' => now(),
        'eind_datum' => now()->addDays(3), // Total leave duration: 4 days
    ]);

    $this->actingAs($user);

    $response = $this->post(route('keuring.updateStatus', $verlofAanvraag->id), [
        'status' => 1,
    ]);

    $response->assertRedirect(route('keuring.index'));
    $response->assertSessionHas('success', 'Status succesvol bijgewerkt.');

    $this->assertDatabaseHas('verlofaanvragen', [
        'id' => $verlofAanvraag->id,
        'status' => 1,
    ]);

    // Updated expected days calculation
    $this->assertDatabaseHas('user_info', [
        'id' => $userInfo->id,
        'verlof_dagen' => 6, // Adjusted from 10 - 4 (leave days)
    ]);
}


}
