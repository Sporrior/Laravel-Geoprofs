<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VerlofAanvragen;
use Illuminate\Support\Facades\DB;


class KeuringController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    // In KeuringController.php
    public function index()
    {
        // Haal alle verlofaanvragen op met de gebruikers- en type-relaties
        $verlofaanvragens = VerlofAanvragen::with('user', 'type')->get();

        // Pass de variabele naar de view
        return view('keuring', compact('verlofaanvragens'));
    }
    public function getUserVerlofaanvragen()
    {
        $userId = auth()->user()->id; // Haal het ID van de ingelogde gebruiker op
        return VerlofAanvragen::with('type')
            ->where('user_id', $userId)
            ->get();
    }

    public function mijnAanvragen()
    {
        $user = auth()->user();
        $mijnAanvragen = VerlofAanvragen::where('user_id', (int) $user->id)->get();

        // Voeg $verlofaanvragens hier toe als je die ook nodig hebt in de view
        $verlofaanvragens = VerlofAanvragen::with('user', 'type')->get();

        return view('dashboard', compact('mijnAanvragen', 'verlofaanvragens'));
    }

    public function updateStatus(Request $request, $id)
    {
        // Find the leave request by ID
        $verlofAanvraag = VerlofAanvragen::findOrFail($id);

        // Update the status based on the user's selection
        $verlofAanvraag->status = $request->input('status');
        $verlofAanvraag->save();

        // Redirect back to the keuring page with a success message
        return redirect()->route('keuring.index')->with('success', 'Status succesvol bijgewerkt.');
    }
}
