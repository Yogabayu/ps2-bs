<?php

namespace App\Http\Controllers\Spv;

use App\Http\Controllers\Controller;
use App\Models\Datas;
use App\Models\Place_transcs;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // dd(Auth::user()->uuid);
            $now = Carbon::now();
            $month = $now->month;
            $app = Setting::first();
            $totalActiveTrans = User::where('isActive', 1)->count();

            $totalData = DB::table('subordinates as s')
                ->join('users as u', 's.subordinate_uuid', '=', 'u.uuid')
                ->join('datas as d', 'u.uuid', '=', 'd.user_uuid')
                ->where('s.supervisor_id', Auth::user()->uuid)
                ->count();

            $totalResult1 = DB::table('subordinates as s')
                ->join('users as u', 's.subordinate_uuid', '=', 'u.uuid')
                ->join('datas as d', 'u.uuid', '=', 'd.user_uuid')
                ->where('s.supervisor_id', Auth::user()->uuid)
                ->where('d.result', 1)
                ->count();

            $totalResult0 = DB::table('subordinates as s')
                ->join('users as u', 's.subordinate_uuid', '=', 'u.uuid')
                ->join('datas as d', 'u.uuid', '=', 'd.user_uuid')
                ->where('s.supervisor_id', Auth::user()->uuid)
                ->where('d.result', 0)
                ->count();

            $totalThisMonth = DB::table('subordinates as s')
                ->join('users as u', 's.subordinate_uuid', '=', 'u.uuid')
                ->join('datas as d', 'u.uuid', '=', 'd.user_uuid')
                ->where('s.supervisor_id', Auth::user()->uuid)
                ->whereMonth('d.date', $month)
                ->count();

            //untuk grafik1
            $dataGrafikChart = DB::table('subordinates as s')
                ->join('users as u', 's.subordinate_uuid', '=', 'u.uuid')
                ->join('datas as d', 'u.uuid', '=', 'd.user_uuid')
                ->where('s.supervisor_id', Auth::user()->uuid)
                ->select(DB::raw('DATE_FORMAT(d.date, "%Y-%m") as month'), DB::raw('COUNT(d.id) as total'))
                ->groupBy('month')
                ->get();

            $dataGrafikChartPie = DB::table('subordinates as s')
                ->join('users as u', 's.subordinate_uuid', '=', 'u.uuid')
                ->join('datas as d', 'u.uuid', '=', 'd.user_uuid')
                ->where('s.supervisor_id', Auth::user()->uuid)
                ->select(
                    DB::raw("SUM(CASE WHEN d.result = 1 THEN 1 ELSE 0 END) as ontime"),
                    DB::raw("SUM(CASE WHEN d.result = 0 THEN 1 ELSE 0 END) as outtime")
                )
                ->get();
            // dd($dataGrafikChart);

            return view("pages.spv.dashboard", compact(
                'app',
                'totalActiveTrans',
                'totalData',
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
