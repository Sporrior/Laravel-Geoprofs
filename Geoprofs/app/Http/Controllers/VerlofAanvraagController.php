<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\VerlofAanvragen;
use App\Models\UserInfo;
use App\Models\Type;
use Carbon\Carbon;

class VerlofAanvraagController extends Controller
{
    public function create(): \Illuminate\View\View
    {
        $types = Type::all();
        $user_info = UserInfo::with(['role', 'team'])->findOrFail(Auth::id());

        return view('verlofaanvragen', compact('types', 'user_info'));
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $user_info = UserInfo::findOrFail(Auth::id());

        $pendingRequestsCount = VerlofAanvragen::where('user_id', $user_info->id)
            ->whereNull('status')
            ->count();

        if ($pendingRequestsCount >= 2) {
            return redirect()->back()->with('error', 'U heeft al twee openstaande verlofaanvragen. Wacht tot deze zijn verwerkt voordat u een nieuwe aanvraag indient.');
        }

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

        $requestedDays = Carbon::parse($startDatum)->diffInDays(Carbon::parse($eindDatum)) + 1;

        if ($requestedDays > $user_info->verlof_dagen) {
            Log::info('Verlofaanvraag geweigerd: aangevraagde dagen overschrijden beschikbare verlofdagen.');
            return redirect()->back()->with('error', 'Verlofaanvraag geweigerd: Aangevraagde dagen overschrijden beschikbare verlofdagen.');
        }

        try {
            $verlofAanvraag = new VerlofAanvragen();
            $verlofAanvraag->user_id = $user_info->id;
            $verlofAanvraag->start_datum = $startDatum;
            $verlofAanvraag->eind_datum = $eindDatum;
            $verlofAanvraag->verlof_reden = $request->verlof_reden;
            $verlofAanvraag->verlof_soort = $request->verlof_soort;
            $verlofAanvraag->aanvraag_datum = Carbon::now();
            $verlofAanvraag->save();

            Log::info('Nieuwe verlofaanvraag van gebruiker ID: ' . $user_info->id);
            return redirect()->back()->with('success', 'Verlofaanvraag succesvol verzonden.');
        } catch (\Exception $e) {
            Log::error('Failed to store leave request', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Er is iets misgegaan bij het verzenden van de verlofaanvraag.');
        }
    }
}
