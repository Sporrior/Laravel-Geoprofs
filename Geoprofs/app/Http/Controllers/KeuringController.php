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

        // Retrieve the new status from the request
        $status = $request->input('status');

        // Check if the new status is 'approved' (1) and if it wasn't approved before
        if ($status == 1 && $verlofAanvraag->status !== 1) {
            // Convert start and end dates to Carbon instances
            $startDatum = Carbon::parse($verlofAanvraag->start_datum);
            $eindDatum = Carbon::parse($verlofAanvraag->eind_datum);

            // Calculate the number of requested days
            $requestedDays = $startDatum->diffInDays($eindDatum) + 1;

            // Retrieve the user associated with the leave request
            $user = $verlofAanvraag->user;

            // Subtract the requested days from the user's available leave days
            $user->verlof_dagen -= $requestedDays;
            $user->save();
        }

        // Update the status on the leave request
        $verlofAanvraag->status = $status;
        $verlofAanvraag->save();

        // Redirect back to the keuring page with a success message
        return redirect()->route('keuring.index')->with('success', 'Status succesvol bijgewerkt.');
    }
}
