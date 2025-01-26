<?php
namespace Tests\Unit\damien;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Verlofaanvragen;
use Carbon\Carbon;

class CalendarControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_het_retourneert_kalendergegevens_voor_indexpagina()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        
        $verlofaanvraag = Verlofaanvragen::factory()->create([
            'user_id' => $user->id,
            'start_datum' => now(),
            'eind_datum' => now(),
            'status' => 1,
        ]);
        
        $response = $this->get(route('calendar.index'));
        $response->assertStatus(200);
        $response->assertViewHas('dagen');
        $response->assertViewHas('user_info');
    }

    public function test_het_bereidt_dagen_voor_de_kalender_voor()
    {
        $user = User::factory()->create();
        $startVanDeWeek = now()->startOfWeek();
        
        Verlofaanvragen::factory()->create([
            'user_id' => $user->id,
            'start_datum' => $startVanDeWeek->copy()->addDay(),
            'eind_datum' => $startVanDeWeek->copy()->addDay(),
            'status' => 1,
        ]);
        
        $controller = new \App\Http\Controllers\CalendarController();
        $kalenderGegevens = $controller->getCalendarData();
        
        $this->assertIsArray($kalenderGegevens['dagen']);
        $this->assertGreaterThan(0, count($kalenderGegevens['dagen']));
        $this->assertArrayHasKey('datumNummer', $kalenderGegevens['dagen'][0]);
        $this->assertArrayHasKey('verlofaanvragen', $kalenderGegevens['dagen'][0]);
    }

    public function test_het_filtert_verlofaanvragen_op_datum()
    {
        $user = User::factory()->create();
        $startVanDeWeek = now()->startOfWeek();
        
        Verlofaanvragen::factory()->create([
            'user_id' => $user->id,
            'start_datum' => $startVanDeWeek,
            'eind_datum' => $startVanDeWeek,
            'status' => 1,
        ]);
        
        Verlofaanvragen::factory()->create([
            'user_id' => $user->id,
            'start_datum' => $startVanDeWeek->copy()->addDay(),
            'eind_datum' => $startVanDeWeek->copy()->addDay(),
            'status' => 1,
        ]);
        
        $controller = new \App\Http\Controllers\CalendarController();
        $kalenderGegevens = $controller->getCalendarData();
        
        $this->assertCount(1, $kalenderGegevens['dagen'][0]['verlofaanvragen']);
        $this->assertCount(1, $kalenderGegevens['dagen'][1]['verlofaanvragen']);
    }
}
