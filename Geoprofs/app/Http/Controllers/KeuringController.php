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
        // Find the leave request by ID
        $verlofAanvraag = VerlofAanvragen::findOrFail($id);
        $currentStatus = $verlofAanvraag->status;
        $newStatus = $request->input('status');

        // Retrieve the user associated with the leave request
        $user = $verlofAanvraag->user;

        // Calculate the number of requested days
        $startDatum = Carbon::parse($verlofAanvraag->start_datum);
        $eindDatum = Carbon::parse($verlofAanvraag->eind_datum);
        $requestedDays = $startDatum->diffInDays($eindDatum) + 1;

        // Check if the status is being updated from approved to rejected
        if ($currentStatus === 1 && $newStatus == 0) {
            // Add the days back to the user's available leave days
            $user->verlof_dagen += $requestedDays;
            $user->save();
        }
        // Check if the status is being updated to approved from any other status
        elseif ($newStatus == 1 && $currentStatus !== 1) {
            // Subtract the requested days from the user's available leave days
            $user->verlof_dagen -= $requestedDays;
            $user->save();
        }

        // Update the status on the leave request
        $verlofAanvraag->status = $newStatus;
        $verlofAanvraag->save();

        // Redirect back to the keuring page with a success message
        return redirect()->route('keuring.index')->with('success', 'Status succesvol bijgewerkt.');
    }
}
