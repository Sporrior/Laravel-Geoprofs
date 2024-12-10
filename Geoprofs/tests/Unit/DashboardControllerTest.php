<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\UserInfo;
use App\Models\Verlofaanvragen;
use App\Models\Type;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_index_displays_correct_data()
    {
        $user = User::factory()->create();
        UserInfo::factory()->create(['id' => $user->id]);

        $verlofaanvragen = Verlofaanvragen::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);

        $response->assertViewHas('verlofaanvragen', function ($data) use ($verlofaanvragen) {
            return $data->count() === $verlofaanvragen->count();
        });
    }

    public function test_lopende_aanvragen_fetches_correct_pending_requests()
    {
        $user = User::factory()->create();
        UserInfo::factory()->create(['id' => $user->id]);

        $type = Type::factory()->create();

        $pendingAanvraag = Verlofaanvragen::factory()->create([
            'user_id' => $user->id,
            'verlof_soort' => $type->id,
            'status' => null,
        ]);

        Verlofaanvragen::factory()->create([
            'user_id' => $user->id,
            'verlof_soort' => $type->id,
            'status' => 1,
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);

        $response->assertViewHas('lopendeAanvragen', function ($data) use ($pendingAanvraag) {
            return $data->count() === 1 &&
                   $data->first()->id === $pendingAanvraag->id &&
                   $data->first()->status === null;
        });
    }
}