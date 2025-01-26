<?php

namespace Tests\Unit\damien;

use App\Models\User;
use App\Models\UserInfo;
use App\Models\Verlofaanvragen;
use App\Models\Type;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_index_toont_juiste_data()
    {
        $user = User::factory()->create();
        UserInfo::factory()->create([
            'id' => $user->id,
            'voornaam' => 'John',
            'achternaam' => 'Doe',
            'email' => 'john.doe@example.com',
            'telefoon' => '0123456789',
            'verlof_dagen' => 20,
            'failed_login_attempts' => 0,
            'blocked_until' => null,
            'role_id' => 1,
            'team_id' => 1,
        ]);

        $verlofaanvragen = Verlofaanvragen::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('verlofaanvragen');
        $response->assertViewHas('lopendeAanvragen');
        $response->assertViewHas('vakantiedagen');
        $response->assertViewHas('dagen');
    }

    public function test_dashboard_index_toont_juiste_lopende_aanvraag()
    {
        $user = User::factory()->create();
        UserInfo::factory()->create([
            'id' => $user->id,
            'voornaam' => 'John',
            'achternaam' => 'Doe',
            'email' => 'john.doe@example.com',
            'telefoon' => '0123456789',
            'verlof_dagen' => 20,
            'failed_login_attempts' => 0,
            'blocked_until' => null,
            'role_id' => 1,
            'team_id' => 1,
        ]);

        $type = Type::factory()->create();

        $lopendeAanvraag = Verlofaanvragen::factory()->create([
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
        $response->assertViewHas('lopendeAanvragen', function ($data) use ($lopendeAanvraag) {
            return $data->count() === 1 && $data->first()->id === $lopendeAanvraag->id;
        });
    }
}
