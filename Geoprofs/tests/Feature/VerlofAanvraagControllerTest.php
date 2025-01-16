<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\UserInfo;
use App\Models\Type;
use App\Models\VerlofAanvragen;
use Carbon\Carbon;

class VerlofAanvraagControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the create method.
     */
    public function testCreateMethod()
{
    $user = User::factory()->create();
    $userInfo = UserInfo::factory()->create(['id' => $user->id]);

    $types = Type::factory(3)->create();

    $this->actingAs($user);

    $response = $this->get(route('verlofaanvragen.create'));

    $response->assertStatus(200);
    $response->assertViewHas('types', function ($viewTypes) use ($types) {
        return $types->pluck('id')->diff($viewTypes->pluck('id'))->isEmpty();
    });
    $response->assertViewHas('user_info', function ($viewUserInfo) use ($userInfo) {
        return $viewUserInfo->id === $userInfo->id;
    });
}

    /**
     * Test the store method with valid data.
     */
    public function testStoreMethodWithValidData()
{
    $user = User::factory()->create();
    $userInfo = UserInfo::factory()->create(['id' => $user->id, 'verlof_dagen' => 10]);

    $type = Type::factory()->create();

    $this->actingAs($user);

    $response = $this->post(route('verlofaanvragen.store'), [
        'startDatum' => '10-12-2024',
        'eindDatum' => '12-12-2024',
        'verlof_reden' => 'Vakantie',
        'verlof_soort' => $type->id,
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success', 'Verlofaanvraag succesvol verzonden.');

    $this->assertDatabaseHas('verlofaanvragen', [
        'user_id' => $userInfo->id,
        'verlof_reden' => 'Vakantie',
        'verlof_soort' => $type->id,
        'start_datum' => '2024-12-10 00:00:00',
        'eind_datum' => '2024-12-12 00:00:00',
    ]);
}


    /**
     * Test the store method with invalid data (exceeds available leave days).
     */
    public function testStoreMethodWithExceedingLeaveDays()
    {
        $user = User::factory()->create();
        $userInfo = UserInfo::factory()->create(['id' => $user->id, 'verlof_dagen' => 2]);

        $type = Type::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('verlofaanvragen.store'), [
            'startDatum' => '10-12-2024',
            'eindDatum' => '15-12-2024',
            'verlof_reden' => 'Lang vakantie',
            'verlof_soort' => $type->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Verlofaanvraag geweigerd: Aangevraagde dagen overschrijden beschikbare verlofdagen.');

        $this->assertDatabaseMissing('verlofaanvragen', [
            'user_id' => $userInfo->id,
        ]);
    }

    /**
     * Test the store method with too many pending requests.
     */
    public function testStoreMethodWithTooManyPendingRequests()
    {
        $user = User::factory()->create();
        $userInfo = UserInfo::factory()->create(['id' => $user->id]);

        $type = Type::factory()->create();

        VerlofAanvragen::factory(2)->create([
            'user_id' => $userInfo->id,
            'status' => null,
        ]);

        $this->actingAs($user);

        $response = $this->post(route('verlofaanvragen.store'), [
            'startDatum' => '10-12-2024',
            'eindDatum' => '12-12-2024',
            'verlof_reden' => 'Weekendje weg',
            'verlof_soort' => $type->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'U heeft al twee openstaande verlofaanvragen. Wacht tot deze zijn verwerkt voordat u een nieuwe aanvraag indient.');

        $this->assertDatabaseCount('verlofaanvragen', 2);
    }
}
