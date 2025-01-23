<?php

namespace App\Http\Controllers;

use App\Models\VerlofAanvragen;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ZiekmeldenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user_info = UserInfo::with(['role', 'team'])->findOrFail(Auth::id());

        return view('ziekmelden', compact('user_info'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'verlof_reden' => 'required|string|max:255',
        ]);
    
        $alreadySick = VerlofAanvragen::where('user_id', Auth::id())
            ->where('verlof_soort', 1)
            ->whereDate('aanvraag_datum', Carbon::today())
            ->exists();
    
        if ($alreadySick) {
            return redirect()->route('ziekmelden.index')->with('error', 'Je hebt je vandaag al ziek gemeld.');
        }
    
        VerlofAanvragen::create([
            'verlof_reden' => $request->input('verlof_reden'),
            'aanvraag_datum' => Carbon::now()->format('Y-m-d'),
            'start_datum' => Carbon::now()->format('Y-m-d'),
            'eind_datum' => Carbon::now()->format('Y-m-d'),
            'verlof_soort' => 1,
            'user_id' => Auth::id(),
            'status' => 1,
        ]);
    
        return redirect()->route('ziekmelden.index')->with('success', 'Ziekmelding succesvol ingediend.');
    }
}