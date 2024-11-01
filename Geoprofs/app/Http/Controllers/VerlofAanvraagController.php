<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\VerlofAanvragen;
use App\Models\Type;
use Carbon\Carbon;

class VerlofAanvraagController extends Controller
{
    public function create()
    {
        $types = Type::all();

        return view('verlofaanvragen', compact('types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'startDatum' => 'required|date',
            'eindDatum' => 'required|date|after_or_equal:startDatum',
            'verlof_reden' => 'required|string|max:1000',
            'verlof_soort' => 'required|exists:types,id',
        ]);

        $verlofAanvraag = new Verlofaanvragen();
        $verlofAanvraag->user_id = Auth::id();
        $verlofAanvraag->start_datum = $request->startDatum;
        $verlofAanvraag->eind_datum = $request->eindDatum;
        $verlofAanvraag->verlof_reden = $request->verlof_reden;
        $verlofAanvraag->verlof_soort = $request->verlof_soort;
        $verlofAanvraag->aanvraag_datum = Carbon::now();
        $verlofAanvraag->save();

        Log::info('Nieuwe verlofaanvraag van gebruiker ID: ' . Auth::id());

        return redirect()->back()->with('success', 'Verlofaanvraag succesvol verzonden.');
    }
}
