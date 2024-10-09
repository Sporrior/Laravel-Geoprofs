<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Ziekmelding;

class ZiekmeldingController extends Controller
{
    public function create()
    {
        return view('ziekmelding');
    }

    public function store(Request $request)
    {
        $request->validate([
            'datum' => 'required|date',
            'reden' => 'required|string|max:1000',
            'verwachteHerstelDatum' => 'required|date|after_or_equal:datum',
        ]);

        $ziekmelding = new Ziekmelding();
        $ziekmelding->user_id = Auth::id();
        $ziekmelding->datum = $request->datum;
        $ziekmelding->reden = $request->reden;
        $ziekmelding->verwachte_herstel_datum = $request->verwachteHerstelDatum;
        $ziekmelding->save();

        Log::info('Nieuwe ziekmelding van gebruiker ID: ' . Auth::id());

        return redirect()->back()->with('success', 'Ziekmelding succesvol verzonden.');
    }
}
