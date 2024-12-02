<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\VerlofAanvragen;
use Carbon\Carbon;

class KeuringControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the index method fetches leave requests and displays them.
     */
    public function test_index_fetches_leave_requests_and_displays_view()
    {
        $user = User::factory()->create([
            'voornaam' => 'Test',
            'achternaam' => 'User',
            'telefoon' => '123456789',
        ]);

        $this->actingAs($user);

        VerlofAanvragen::factory()->count(3)->create();

        $response = $this->get(route('keuring.index'));

        $response->assertStatus(200);
        $response->assertViewIs('keuring');
        $response->assertViewHas('verlofaanvragens');
    }

    /**
     * Test updateStatus validates required fields and updates leave status.
     */
    public function test_update_status_validates_required_fields_and_updates_status()
    {
        $user = User::factory()->create([
            'voornaam' => 'Test',
            'achternaam' => 'User',
            'telefoon' => '123456789',
            'verlof_dagen' => 20,
        ]);

        $this->actingAs($user);

        $verlofAanvraag = VerlofAanvragen::factory()->create([
            'user_id' => $user->id,
            'verlof_soort' => 2,
            'start_datum' => Carbon::now(),
            'eind_datum' => Carbon::now()->addDays(2),
            'status' => 0,
        ]);

        $response = $this->post(route('keuring.update', $verlofAanvraag->id), [
            'status' => 1,
        ]);

        $response->assertRedirect(route('keuring.index'));
        $response->assertSessionHas('success', 'Status succesvol bijgewerkt.');

        $this->assertDatabaseHas('verlof_aanvragens', [
            'id' => $verlofAanvraag->id,
            'status' => 1,
        ]);

        $user->refresh();
        $this->assertEquals(17, $user->verlof_dagen); // 20 - 3 days
    }

    /**
     * Test rejection requires weigerreden and restores leave days.
     */
    public function test_rejection_requires_weigerreden_and_restores_leave_days()
    {
        $user = User::factory()->create([
            'voornaam' => 'Test',
            'achternaam' => 'User',
            'telefoon' => '123456789',
            'verlof_dagen' => 15,
        ]);

        $this->actingAs($user);

        $verlofAanvraag = VerlofAanvragen::factory()->create([
            'user_id' => $user->id,
            'verlof_soort' => 2,
            'start_datum' => Carbon::now(),
            'eind_datum' => Carbon::now()->addDay(),
            'status' => 1,
        ]);

        $response = $this->post(route('keuring.update', $verlofAanvraag->id), [
            'status' => 0,
        ]);

        $response->assertSessionHasErrors(['weigerreden']);

        $response = $this->post(route('keuring.update', $verlofAanvraag->id), [
            'status' => 0,
            'weigerreden' => 'Reason for rejection',
        ]);

        $response->assertRedirect(route('keuring.index'));
        $response->assertSessionHas('success', 'Status succesvol bijgewerkt.');

        $this->assertDatabaseHas('verlof_aanvragens', [
            'id' => $verlofAanvraag->id,
            'status' => 0,
            'weigerreden' => 'Reason for rejection',
        ]);

        $user->refresh();
        $this->assertEquals(17, $user->verlof_dagen); // 15 + 2 days restored
    }
}