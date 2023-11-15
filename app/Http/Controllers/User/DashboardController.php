<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Datas;
use App\Models\Place_transcs;
use App\Models\Setting;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $now = Carbon::now();
            $month = $now->month;

            $app = Setting::first();
            $totalData = Datas::where('user_uuid', Auth::user()->uuid)->count();
            $totalResult1 = Datas::where('user_uuid', Auth::user()->uuid)->where('result', 1)->count();
            $totalResult0 = Datas::where('user_uuid', Auth::user()->uuid)->where('result', 0)->count();
            $totalThisMonth = Datas::where('user_uuid', Auth::user()->uuid)->whereMonth('date', $month)->count();
            $transactions = Transaction::where('position_id', Auth::user()->position_id)->get();
            $places = Place_transcs::all();

            //untuk grafik1
            $dataGrafikChart = DB::table('datas')
                ->where('user_uuid', Auth::user()->uuid)
                ->select(DB::raw('DATE_FORMAT(date, "%Y-%m") as month'), DB::raw('COUNT(*) as total'))
                ->groupBy('month')
                ->get();
            //untuk grafik 2
            $dataGrafikChartPie = DB::table('datas')
                ->where('user_uuid', Auth::user()->uuid)
                ->select(
                    DB::raw("SUM(CASE WHEN result = 1 THEN 1 ELSE 0 END) as ontime"),
                    DB::raw("SUM(CASE WHEN result = 0 THEN 1 ELSE 0 END) as outtime")
                )
                ->get();

            return view("pages.user.dashboard", compact(
                "transactions",
                "places",
                "app",
                "totalData",
                "totalThisMonth",
                "totalResult1",
                "totalResult0",
                "dataGrafikChart",
                "dataGrafikChartPie"
            ));
        } catch (\Exception $e) {
            Alert::toast('Error : ' . $e->getMessage(), 'error');
            return redirect()->back();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
