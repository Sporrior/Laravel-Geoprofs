<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Verlofaanvragen;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index()
    {
        $user = Auth::user();

        $user_info = $user->load(['role', 'team']);

        $verlofaanvragen = Verlofaanvragen::with('user')->get();

        foreach ($verlofaanvragen as $aanvraag) {
            $aanvraag->status_label = $this->getStatusLabel($aanvraag->status);
        }

        $lopendeAanvragen = $this->lopendeAanvragen();

        return view("dashboard", [
            "user" => $user,
            "user_info" => $user_info,
            "verlofaanvragen" => $verlofaanvragen,
            "lopendeAanvragen" => $lopendeAanvragen,
            "vakantiedagen" => $user_info->verlof_dagen,
        ]);
    }

    public function lopendeAanvragen()
    {
        $user = Auth::user();

        $verlofaanvragen = Verlofaanvragen::with("user")
            ->where([
                ['status', '=', null],
                ['verlof_soort', '=', 4], 
                ['user_id', '=', $user->id],
            ])
            ->get();

        // Add status labels to each leave request
        foreach ($verlofaanvragen as $aanvraag) {
            $aanvraag->status_label = $this->getStatusLabel($aanvraag->status);
        }

        return $verlofaanvragen;
    }

    /**
     * Get the status label for a leave request.
     *
     * @param int|null $status
     * @return string
     */
    private function getStatusLabel($status)
    {
        if (is_null($status)) {
            return "Afwachting"; // Pending
        }

        return $status === 1 ? "Goedgekeurd" : "Geweigerd"; // Approved or Rejected
    }
}