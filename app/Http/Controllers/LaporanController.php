<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataInvestasi;

class LaporanController extends Controller
{
    public function index()
    {
        $data_investasi = DataInvestasi::all();
        return view('admin.laporan', compact('data_investasi'));
    }
}
