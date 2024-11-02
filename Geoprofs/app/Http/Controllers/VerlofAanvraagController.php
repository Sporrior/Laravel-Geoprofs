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
    public function create(): \Illuminate\View\View
    {
        $types = Type::all();
        return view('verlofaanvragen', compact('types'));
    }

    public function showDashboard(): \Illuminate\View\View
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        // Fetch leave applications for the current week along with the type name
        $verlofaanvragen = VerlofAanvragen::whereBetween('start_datum', [$startOfWeek, $endOfWeek])
            ->orWhereBetween('eind_datum', [$startOfWeek, $endOfWeek])
            ->leftJoin('types', 'verlofaanvragens.verlof_soort', '=', 'types.id')
            ->select('verlofaanvragens.*', 'types.type as type_name') // Select type name from types table
            ->get()
            ->map(function ($item) {
                return [
                    'start_datum' => $item->start_datum->format('Y-m-d'),
                    'eind_datum' => $item->eind_datum->format('Y-m-d'),
                    'status' => $item->status ? 'Ziek' : 'Vrij',
                    'type_name' => $item->type_name ?? 'Onbekend', // Use 'Onbekend' as fallback if type_name is null
                ];
            });

        // Log the data for debugging
        Log::info('Verlofaanvragen data:', ['verlofaanvragen' => $verlofaanvragen->toArray()]);

        return view('dashboard', [
            'verlofaanvragen' => $verlofaanvragen,
        ]);
    }





    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        // Convert the date format from d-m-Y to Y-m-d for validation and saving
        $startDatum = Carbon::createFromFormat('d-m-Y', $request->startDatum)->format('Y-m-d');
        $eindDatum = Carbon::createFromFormat('d-m-Y', $request->eindDatum)->format('Y-m-d');

        // Replace the original input with the converted dates
        $request->merge([
            'startDatum' => $startDatum,
            'eindDatum' => $eindDatum,
        ]);

        // Now validate the converted dates
        $request->validate([
            'startDatum' => 'required|date',
            'eindDatum' => 'required|date|after_or_equal:startDatum',
            'verlof_reden' => 'required|string|max:1000',
            'verlof_soort' => 'required|exists:types,id',
        ]);

        try {
            $verlofAanvraag = new VerlofAanvragen();
            $verlofAanvraag->user_id = Auth::id();
            $verlofAanvraag->start_datum = $startDatum;
            $verlofAanvraag->eind_datum = $eindDatum;
            $verlofAanvraag->verlof_reden = $request->verlof_reden;
            $verlofAanvraag->verlof_soort = $request->verlof_soort;
            $verlofAanvraag->aanvraag_datum = Carbon::now();
            $verlofAanvraag->save();

            Log::info('Nieuwe verlofaanvraag van gebruiker ID: ' . Auth::id());
            return redirect()->back()->with('success', 'Verlofaanvraag succesvol verzonden.');
        } catch (\Exception $e) {
            Log::error('Failed to store leave request', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Er is iets misgegaan bij het verzenden van de verlofaanvraag.');
        }
    }
}
