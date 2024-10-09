<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\VerlofAanvraag;

class VerlofAanvraagController extends Controller
{
    public function create()
    {
        return view('verlofaanvragen'); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'startDatum' => 'required|date',
            'eindDatum' => 'required|date|after_or_equal:startDatum',
            'reden' => 'required|string|max:1000',
        ]);

        $verlofAanvraag = new Verlofaanvraag();
        $verlofAanvraag->user_id = Auth::id();
        $verlofAanvraag->start_datum = $request->startDatum;
        $verlofAanvraag->eind_datum = $request->eindDatum;
        $verlofAanvraag->reden = $request->reden;
        $verlofAanvraag->save();

        Log::info('Nieuwe verlofaanvraag van gebruiker ID: ' . Auth::id());

        return redirect()->back()->with('success', 'Verlofaanvraag succesvol verzonden.');
    }
}
