<?php

namespace App\Http\Controllers;

use App\Models\Verlofaanvragen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index()
    {
        $user = Auth::user();

        $verlofaanvragen = Verlofaanvragen::with("user")->get();

        foreach ($verlofaanvragen as $aanvraag) {
            if (is_null($aanvraag->status)) {
                $aanvraag->status_label = "Afwachting";
            } elseif ($aanvraag->status === 1) {
                $aanvraag->status_label = "Goedgekeurd";
            } else {
                $aanvraag->status_label = "Geweigerd";
            }
        }

        // Roep de lopendeAanvragen() functie aan
        $lopendeAanvragen = $this->lopendeAanvragen();

        return view("dashboard", [
            "verlofaanvragen" => $verlofaanvragen,
            "lopendeAanvragen" => $lopendeAanvragen,
            "vakantiedagen" => $user->verlof_dagen,
        ]);
    }

    public function lopendeAanvragen()
{
    $user = Auth::user();

    // Haal alle relevante verlofaanvragen op
    $verlofaanvragen = Verlofaanvragen::with("user")
        ->where([
            ['status', '=', null],
            ['verlof_soort', '=', 4],
            ['user_id', '=', $user->id],
        ])
        ->get();

    // Voeg status labels toe aan elke aanvraag
    foreach ($verlofaanvragen as $aanvraag) {
        if (is_null($aanvraag->status)) {
            $aanvraag->status_label = "Afwachting";
        } elseif ($aanvraag->status === 1) {
            $aanvraag->status_label = "Goedgekeurd";
        } else {
            $aanvraag->status_label = "Geweigerd";
        }
    }

    return $verlofaanvragen;
}
}
