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

    public function index()
    {
        // Fetch all leave requests (verlofaanvragens)
        $verlofaanvragens = VerlofAanvragen::with('user', 'type')->get();

        // Pass them to the view
        return view('keuring', compact('verlofaanvragens'));
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
