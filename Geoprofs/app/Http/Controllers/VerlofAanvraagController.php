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
        // Retrieve approved leave requests, ordered by start date descending
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $verlofaanvragen = VerlofAanvragen::where('status', 1)
            ->whereBetween('start_datum', [$startOfWeek, $endOfWeek])
            ->orWhereBetween('eind_datum', [$startOfWeek, $endOfWeek])
            ->leftJoin('types', 'verlofaanvragens.verlof_soort', '=', 'types.id')
            ->select('verlofaanvragens.*', 'types.type as type_name')
            ->orderBy('start_datum', 'desc') // Sort by start_datum instead of aanvraag_datum
            ->get()
            ->map(function ($item) {
                return [
                    'start_datum' => $item->start_datum->format('Y-m-d'),
                    'eind_datum' => $item->eind_datum->format('Y-m-d'),
                    'status' => $item->status ? 'Ziek' : 'Vrij',
                    'type_name' => $item->type_name ?? 'Onbekend',
                    'verlof_reden' => $item->verlof_reden,
                ];
            });

        return view('dashboard', [
            'verlofaanvragen' => $verlofaanvragen,
        ]);
    }


    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $startDatum = Carbon::createFromFormat('d-m-Y', $request->startDatum)->format('Y-m-d');
        $eindDatum = Carbon::createFromFormat('d-m-Y', $request->eindDatum)->format('Y-m-d');

        $request->merge([
            'startDatum' => $startDatum,
            'eindDatum' => $eindDatum,
        ]);

        $request->validate([
            'startDatum' => 'required|date',
            'eindDatum' => 'required|date|after_or_equal:startDatum',
            'verlof_reden' => 'required|string|max:1000',
            'verlof_soort' => 'required|exists:types,id',
        ]);

        // Calculate requested days
        $requestedDays = Carbon::parse($startDatum)->diffInDays(Carbon::parse($eindDatum)) + 1;

        // Fetch the user's available leave days
        $user = Auth::user();
        $availableDays = $user->verlof_dagen;

        // Check if the requested days exceed available leave days
        if ($requestedDays > $availableDays) {
            Log::info('Verlofaanvraag geweigerd: aangevraagde dagen overschrijden beschikbare verlofdagen.');
            return redirect()->back()->with('error', 'Verlofaanvraag geweigerd: Aangevraagde dagen overschrijden beschikbare verlofdagen.');
        }

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
