<?php

namespace App\Http\Controllers;

use App\Models\Verlofaanvragen;
use App\Models\UserInfo;
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

        $user_info = UserInfo::with(['role', 'team'])->findOrFail($user->id);

        $verlofaanvragen = Verlofaanvragen::with('user')
            ->where('user_id', $user->id)
            ->get()
            ->map(function ($aanvraag) {
                $aanvraag->status_label = $this->getStatusLabel($aanvraag->status);
                return $aanvraag;
            });

        $lopendeAanvragen = $this->getLopendeAanvragen($user->id);

        return view("dashboard", [
            "user" => $user,
            "user_info" => $user_info,
            "verlofaanvragen" => $verlofaanvragen,
            "lopendeAanvragen" => $lopendeAanvragen,
            "vakantiedagen" => $user_info->verlof_dagen,
        ]);
    }

    private function getLopendeAanvragen($userId)
    {
        return Verlofaanvragen::where('user_id', $userId)
            ->whereNull('status')
            ->get()
            ->map(function ($aanvraag) {
                $aanvraag->status_label = $this->getStatusLabel($aanvraag->status);
                return $aanvraag;
            });
    }

    private function getStatusLabel($status)
    {
        if (is_null($status)) {
            return "Afwachting";
        }

        return $status === 1 ? "Goedgekeurd" : "Geweigerd";
    }
}