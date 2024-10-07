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
        $this->middleware('auth');
    }

    public function index()
    {

        $user = Auth::user();
        $verlofaanvragen = Verlofaanvragen::all();
        $verlofaanvragen = Verlofaanvragen::with('user')->get();

        foreach ($verlofaanvragen as $aanvraag) {
            // Status is null = Afwachting, 1 = Goedgekeurd, 0 = Geweigerd
            if (is_null($aanvraag->status)) {
                $aanvraag->status_label = 'Afwachting';
            } elseif ($aanvraag->status === 1) {
                $aanvraag->status_label = 'Goedgekeurd';
            } else {
                $aanvraag->status_label = 'Geweigerd';
            }
        }


        return view('dashboard', [
            'verlofaanvragen' => $verlofaanvragen,
            'vakantiedagen' => $user->verlof_dagen
        ]);

    }
}
