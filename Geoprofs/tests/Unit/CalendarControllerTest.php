<?php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Verlofaanvragen;
use Carbon\Carbon;

class CalendarControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_calendar_data_for_index_page()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        
        $leaveRequest = Verlofaanvragen::factory()->create([
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

    public function test_it_prepares_days_for_the_calendar()
    {
        $user = User::factory()->create();
        $startOfWeek = now()->startOfWeek();
        
        Verlofaanvragen::factory()->create([
            'user_id' => $user->id,
            'start_datum' => $startOfWeek->copy()->addDay(),
            'eind_datum' => $startOfWeek->copy()->addDay(),
            'status' => 1,
        ]);
        
        $controller = new \App\Http\Controllers\CalendarController();
        $calendarData = $controller->getCalendarData();
        
        $this->assertIsArray($calendarData['dagen']);
        $this->assertGreaterThan(0, count($calendarData['dagen']));
        $this->assertArrayHasKey('datumNummer', $calendarData['dagen'][0]);
        $this->assertArrayHasKey('verlofaanvragen', $calendarData['dagen'][0]);
    }

    public function test_it_filters_leave_requests_by_date()
    {
        $user = User::factory()->create();
        $startOfWeek = now()->startOfWeek();
        
        Verlofaanvragen::factory()->create([
            'user_id' => $user->id,
            'start_datum' => $startOfWeek,
            'eind_datum' => $startOfWeek,
            'status' => 1,
        ]);
        
        Verlofaanvragen::factory()->create([
            'user_id' => $user->id,
            'start_datum' => $startOfWeek->copy()->addDay(),
            'eind_datum' => $startOfWeek->copy()->addDay(),
            'status' => 1,
        ]);
        
        $controller = new \App\Http\Controllers\CalendarController();
        $calendarData = $controller->getCalendarData();
        
        $this->assertCount(1, $calendarData['dagen'][0]['verlofaanvragen']);
        $this->assertCount(1, $calendarData['dagen'][1]['verlofaanvragen']);
    }
}