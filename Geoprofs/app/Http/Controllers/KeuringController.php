<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VerlofAanvragen;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KeuringController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Fetch all leave requests (verlofaanvragens) with related user and type
        $verlofaanvragens = VerlofAanvragen::with('user', 'type')->get();

        // Pass them to the view
        return view('keuring', compact('verlofaanvragens'));
    }

    public function updateStatus(Request $request, $id)
    {
        $verlofAanvraag = VerlofAanvragen::findOrFail($id);
        $currentStatus = $verlofAanvraag->status;
        $newStatus = $request->input('status');
        $weigerreden = $request->input('weigerreden');

        if ($newStatus == 0) {
            $request->validate([
                'weigerreden' => 'required|string|max:255',
            ]);
            $verlofAanvraag->weigerreden = $weigerreden;
        } else {
            $verlofAanvraag->weigerreden = null;
        }

        // Handle user leave days logic (if applicable)
        if ($verlofAanvraag->verlof_soort == 2) {
            $user = $verlofAanvraag->user;
            $startDatum = Carbon::parse($verlofAanvraag->start_datum);
            $eindDatum = Carbon::parse($verlofAanvraag->eind_datum);
            $requestedDays = $startDatum->diffInDays($eindDatum) + 1;

            if ($currentStatus === 1 && $newStatus == 0) {
                $user->verlof_dagen += $requestedDays;
                $user->save();
            } elseif ($newStatus == 1 && $currentStatus !== 1) {
                $user->verlof_dagen -= $requestedDays;
                $user->save();
            }
        }

        $verlofAanvraag->status = $newStatus;
        $verlofAanvraag->save();

        return redirect()->route('keuring.index')->with('success', 'Status succesvol bijgewerkt.');
    }

}
