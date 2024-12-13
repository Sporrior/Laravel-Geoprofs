<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Verlofaanvragen;
use Illuminate\Support\Facades\Auth;

class CalendarControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_calendar_data_for_index_page()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $leaveRequest = Verlofaanvragen::factory()->create([
            'user_id' => $user->id,
            'start_datum' => now()->addDay(),
            'eind_datum' => now()->addDays(2),
            'status' => 1, 
        ]);

        $response = $this->get(route('calendar.index'));

        $response->assertStatus(200);
        $response->assertViewHas('dagen'); 
        $response->assertViewHas('user_info');  
    }

    /** @test */
    public function it_prepares_days_for_the_calendar()
    {
        $leaveRequest = Verlofaanvragen::factory()->create([
            'user_id' => 1,
            'start_datum' => now()->addDay(),
            'eind_datum' => now()->addDays(2),
            'status' => 1, 
        ]);

        $controller = new \App\Http\Controllers\CalendarController();
        $calendarData = $controller->getCalendarData();

        $this->assertIsArray($calendarData['dagen']);
        $this->assertGreaterThan(0, count($calendarData['dagen']));
        $this->assertArrayHasKey('datumNummer', $calendarData['dagen'][0]); 
        $this->assertArrayHasKey('verlofaanvragen', $calendarData['dagen'][0]); 

        $this->assertGreaterThan(0, count($calendarData['dagen'][1]['verlofaanvragen']));
    }

    /** @test */
    public function it_filters_leave_requests_by_date()
    {
        $leaveRequestToday = Verlofaanvragen::factory()->create([
            'user_id' => 1,
            'start_datum' => now(),
            'eind_datum' => now(),
            'status' => 1, 
        ]);

        $leaveRequestTomorrow = Verlofaanvragen::factory()->create([
            'user_id' => 1,
            'start_datum' => now()->addDay(),
            'eind_datum' => now()->addDay(),
            'status' => 1,
        ]);

        $controller = new \App\Http\Controllers\CalendarController();
        $calendarData = $controller->getCalendarData();

        $this->assertCount(1, $calendarData['dagen'][0]['verlofaanvragen']);
        $this->assertCount(1, $calendarData['dagen'][1]['verlofaanvragen']); 
    }

    /** @test */
    public function it_maps_english_day_names_to_dutch()
    {
        $controller = new \App\Http\Controllers\CalendarController();

        $this->assertEquals('Ma', $controller->getDutchDayName('Mon'));
        $this->assertEquals('Di', $controller->getDutchDayName('Tue'));
        $this->assertEquals('Wo', $controller->getDutchDayName('Wed'));
        $this->assertEquals('Do', $controller->getDutchDayName('Thu'));
        $this->assertEquals('Vr', $controller->getDutchDayName('Fri'));
        $this->assertEquals('Za', $controller->getDutchDayName('Sat'));
        $this->assertEquals('Zo', $controller->getDutchDayName('Sun'));

        $this->assertEquals('Funday', $controller->getDutchDayName('Funday')); 
    }
}
