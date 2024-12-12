<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Verlofaanvragen;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function index()
    {
        // Fetch the logged-in user's user_info
        $user_info = Auth::user(); // Ensure User model includes the necessary relationships

        // Fetch approved leave requests (status = 1)
        $verlofaanvragen = Verlofaanvragen::where('status', 1)
            ->with('user') // Load the related UserInfo data via 'user'
            ->get();

        // Prepare the days with leave requests
        $dagen = $this->prepareDays($verlofaanvragen);

        return view('calendar', compact('dagen', 'user_info'));
    }

    private function prepareDays($verlofaanvragen)
    {
        $dagen = [];
        $startVanWeek = now()->startOfWeek(); // Start of the current week

        for ($i = 0; $i < 7; $i++) {
            $huidigeDatum = $startVanWeek->copy()->addDays($i);

            // Filter leave requests for the current date
            $gefilterdeVerzoeken = $verlofaanvragen->filter(function ($verzoek) use ($huidigeDatum) {
                $startDatum = $verzoek->start_datum;
                $eindDatum = $verzoek->eind_datum;

                return $huidigeDatum->between($startDatum, $eindDatum);
            })->map(function ($verzoek) {
                return [
                    'voornaam' => $verzoek->user->voornaam ?? 'Onbekend', // Access 'voornaam' from the related user
                    'reden' => $verzoek->verlof_reden,
                    'tijd' => $verzoek->start_datum->format('H:i') . ' - ' . $verzoek->eind_datum->format('H:i'),
                    'start' => (int) $verzoek->start_datum->format('G') - 8, // Offset for grid-row-start
                    'end' => (int) $verzoek->eind_datum->format('G') - 8,    // Offset for grid-row-end
                ];
            });

            $dagen[] = [
                'datumNummer' => $huidigeDatum->format('j'),
                'datumDag' => $this->getDutchDayName($huidigeDatum->format('D')),
                'verlofaanvragen' => $gefilterdeVerzoeken,
            ];
        }

        return $dagen;
    }

    private function getDutchDayName($day)
    {
        $dayMap = [
            'Mon' => 'Ma',
            'Tue' => 'Di',
            'Wed' => 'Wo',
            'Thu' => 'Do',
            'Fri' => 'Vr',
            'Sat' => 'Za',
            'Sun' => 'Zo',
        ];

        return $dayMap[$day] ?? $day;
    }
}