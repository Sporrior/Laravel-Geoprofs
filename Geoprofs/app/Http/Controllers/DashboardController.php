<?php

namespace App\Http\Controllers;

use App\Models\Verlofaanvragen;
use App\Models\UserInfo;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index()
    {
        $user = Auth::user();

        $user_info = UserInfo::with(['role', 'team'])->findOrFail($user->id);

        $verlofaanvragen = Verlofaanvragen::with('user')
            ->where('user_id', $user->id)
            ->get()
            ->map(function ($aanvraag) {
                $aanvraag->status_label = $this->getStatusLabel($aanvraag->status);
                return $aanvraag;
            });

        $lopendeAanvragen = $this->getLopendeAanvragen($user->id);

        $allVerlofaanvragen = Verlofaanvragen::where('status', 1)
            ->with('user')
            ->get();

        $dagen = $this->prepareDays($allVerlofaanvragen);

        return view("dashboard", [
            "user" => $user,
            "user_info" => $user_info,
            "verlofaanvragen" => $verlofaanvragen,
            "lopendeAanvragen" => $lopendeAanvragen,
            "vakantiedagen" => $user_info->verlof_dagen,
            "dagen" => $dagen,
        ]);
    }

    private function getLopendeAanvragen($userId)
    {
        return Verlofaanvragen::where('user_id', $userId)
            ->whereNull('status')
            ->get()
            ->map(function ($aanvraag) {
                $aanvraag->status_label = $this->getStatusLabel($aanvraag->status);
                return $aanvraag;
            });
    }

    private function getStatusLabel($status)
    {
        if (is_null($status)) {
            return "Afwachting";
        }

        return $status === 1 ? "Goedgekeurd" : "Geweigerd";
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
            })->map(function ($verzoek, $index) {
                return [
                    'voornaam' => $verzoek->user->voornaam ?? 'Onbekend',
                    'reden' => $verzoek->verlof_reden,
                    'tijd' => $verzoek->start_datum->format('H:i') . ' - ' . $verzoek->eind_datum->format('H:i'),
                    'start' => max((int) $verzoek->start_datum->format('H') - 8 + $index, 1),
                    'end' => max((int) $verzoek->eind_datum->format('H') - 8 + $index + 1, 2),
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