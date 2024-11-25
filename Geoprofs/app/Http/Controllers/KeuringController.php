<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VerlofAanvragen;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\type;
use Carbon\Carbon;

class KeuringController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $types = Type::all();
        $verlofaanvragens = VerlofAanvragen::with('user', 'type');

        if ($request->has('types')) {
            $selectedTypes = $request->input('types');
            $verlofaanvragen = $verlofaanvragen->whereIn('verlof_soort', $selectedTypes);
        }

        if ($request->has('users')) {
            $selectedUsers = $request->input('users');
            $verlofaanvragens = $verlofaanvragens->whereIn('user_id', $selectedUsers);
        }

        $verlofaanvragen = $verlofaanvragen->orderByDesc('updated_at')->get();

        return view('keuring', compact('verlofaanvragens', 'types'));
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
