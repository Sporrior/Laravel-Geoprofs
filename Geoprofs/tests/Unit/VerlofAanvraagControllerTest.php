<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Carbon\Carbon;

class VerlofAanvraagControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Disable middleware for this test class
        $this->withoutMiddleware();
    }

    public function test_verlofaanvraag_denied_when_requested_days_exceed_available_days()
    {
        // Set up Log facade mock before making the request
        Log::shouldReceive('info')
            ->once()
            ->with('Verlofaanvraag geweigerd: aangevraagde dagen overschrijden beschikbare verlofdagen.');

        // Mock authenticated user with 5 available leave days
        $user = User::factory()->make(['verlof_dagen' => 5]); // Using make() to avoid saving in DB
        Auth::shouldReceive('user')->andReturn($user);
        Auth::shouldReceive('id')->andReturn($user->id);
        Auth::shouldReceive('guard')->andReturnSelf(); // Mock guard to prevent errors

        // Define start and end dates that will result in more days than available
        $startDatum = Carbon::today()->format('d-m-Y');
        $eindDatum = Carbon::today()->addDays(6)->format('d-m-Y'); // Results in 7 days requested

        // Mock request input and simulate controller behavior
        $response = $this->post(route('verlofaanvragen.store'), [
            'startDatum' => $startDatum,
            'eindDatum' => $eindDatum,
            'verlof_reden' => 'Personal reason',
            'verlof_soort' => 1, // Assuming a valid leave type ID
        ]);

        // Assert that the user is redirected back
        $response->assertRedirect();

        // Assert that the error message is set in the session
        $response->assertSessionHas('error', 'Verlofaanvraag geweigerd: Aangevraagde dagen overschrijden beschikbare verlofdagen.');
    }
}
