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
    public function index(Request $request)
    {
        try {
            $now = Carbon::now();
            $month = $now->month;
            $app = Setting::first();
            $totalActiveTrans = DB::table('subordinates as s')
                ->join('users as u', 's.subordinate_uuid', '=', 'u.uuid')
                ->where('u.isActive','=',1)
                ->where('u.position_id','!=',1)
                ->where('u.position_id','!=',2)
                ->where('s.supervisor_id', Auth::user()->uuid)
                ->count();

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
            $filter = $request->filter;

            if ($filter === 'week') {
                $dataGrafikChart = DB::table('subordinates as s')
                    ->join('users as u', 's.subordinate_uuid', '=', 'u.uuid')
                    ->join('datas as d', 'u.uuid', '=', 'd.user_uuid')
                    ->where('s.supervisor_id', Auth::user()->uuid)
                    ->select(
                        DB::raw('DATE_FORMAT(d.date, "%x-%v") as unit'),
                        DB::raw('COUNT(d.id) as total')
                    )
                    ->groupBy('unit')
                    ->get();
            } else {
                    $dataGrafikChart = DB::table('subordinates as s')
                    ->join('users as u', 's.subordinate_uuid', '=', 'u.uuid')
                    ->join('datas as d', 'u.uuid', '=', 'd.user_uuid')
                    ->where('s.supervisor_id', Auth::user()->uuid)
                    ->select(DB::raw('DATE_FORMAT(d.date, "%Y-%m") as unit'), DB::raw('COUNT(d.id) as total'))
                    ->groupBy('unit')
                    ->get();
            }

            // $filter2 = $request->filter2;

            $dataGrafikChartPie = DB::table('subordinates as s')
                ->join('users as u', 's.subordinate_uuid', '=', 'u.uuid')
                ->join('datas as d', 'u.uuid', '=', 'd.user_uuid')
                ->where('s.supervisor_id', Auth::user()->uuid)
                ->select(
                    DB::raw("SUM(CASE WHEN d.result = 1 THEN 1 ELSE 0 END) as ontime"),
                    DB::raw("SUM(CASE WHEN d.result = 0 THEN 1 ELSE 0 END) as outtime")
                );

            if ($filter === 'week') {
                $dataGrafikChartPie->whereBetween('d.created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek(),
                ]);
            } else {
                $dataGrafikChartPie->whereMonth('d.created_at', now()->month);
            }

            $dataGrafikChartPie = $dataGrafikChartPie->get();


            return view("pages.spv.dashboard", compact(
                'app',
                'totalActiveTrans',
                'totalData',
                "totalThisMonth",
                "totalResult1",
                "totalResult0",
                "dataGrafikChart",
                "dataGrafikChartPie",
                "filter",
            ));
        } catch (\Exception $e) {
            Alert::toast('Error : ' . $e->getMessage(), 'error');
            return redirect()->back();
        }
    }
}
