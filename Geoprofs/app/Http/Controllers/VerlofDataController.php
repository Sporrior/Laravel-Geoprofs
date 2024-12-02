<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\VerlofaanvragenExport;
use App\Models\VerlofAanvragen;



class VerlofDataController extends Controller
{

    public function index()
    {
        return view('verlofdata');
    }

    public function export()
    {
        return Excel::download(new VerlofaanvragenExport, 'verlofaanvragen.xlsx');
    }
}
