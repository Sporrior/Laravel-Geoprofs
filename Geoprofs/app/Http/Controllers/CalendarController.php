<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Verlofaanvragen;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function index()
    {
        $data = $this->getCalendarData();
        return view('calendar', $data);
    }

    public function getCalendarData()
    {
        $user_info = Auth::user();

        $verlofaanvragen = Verlofaanvragen::where('status', 1)
            ->with('user')
            ->get();

        $dagen = $this->prepareDays($verlofaanvragen);

        return compact('dagen', 'user_info');
    }

    private function prepareDays($verlofaanvragen)
    {
        $dagen = [];
        $startVanWeek = now()->startOfWeek();

        for ($i = 0; $i < 7; $i++) {
            $huidigeDatum = $startVanWeek->copy()->addDays($i);

            $gefilterdeVerzoeken = $verlofaanvragen->filter(function ($verzoek) use ($huidigeDatum) {
                $startDatum = $verzoek->start_datum;
                $eindDatum = $verzoek->eind_datum;

                return $huidigeDatum->between($startDatum, $eindDatum);
            })->map(function ($verzoek) {
                return [
                    'voornaam' => $verzoek->user->voornaam ?? 'Onbekend',
                    'reden' => $verzoek->verlof_reden,
                    'tijd' => $verzoek->start_datum->format('H:i') . ' - ' . $verzoek->eind_datum->format('H:i'),
                    'start' => (int) $verzoek->start_datum->format('G') - 8,
                    'end' => (int) $verzoek->eind_datum->format('G') - 8,
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