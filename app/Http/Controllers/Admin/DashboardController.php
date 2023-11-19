<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Datas;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        try {
            // dd($request->all());
            $app = Setting::first();
            $totalAdmin = User::where('position_id', 1)->count();
            $totalSPV = User::where('position_id', 2)->count();
            $totalUser = User::whereNotIn('position_id', [1, 2])->count();
            $totalActiveTrans = User::where('isActive', 1)->where('position_id','!=',1)->count();
            $totalOnTime = Datas::where('result', 1)->count();
            $totalOutTime = Datas::where('result', 0)->count();
            $userActivities = UserActivity::with('user')->limit(5)->orderBy('id', 'DESC')->get();

            //untuk grafik1
            $filter = $request->filter;

            if ($filter === 'month') {
                $dataGrafikChart = DB::table('datas')
                    ->select(
                        DB::raw('DATE_FORMAT(date, "%Y-%m")' . ' as
                unit'),
                        DB::raw('COUNT(*) as total')
                    )
                    ->groupBy('unit')
                    ->get();
            } else {
                $dataGrafikChart = DB::table('datas')
                    ->select(
                        DB::raw('DATE_FORMAT(date, "%x-%v")' . ' as
                unit'),
                        DB::raw('COUNT(*) as total')
                    )
                    ->groupBy('unit')
                    ->get();
            }



            // dd($dataGrafikChart);
            return view('pages.admin.dashboard', [
                'type_menu' => 'dashboard',
                'app' => $app,
                'totalAdmin' => $totalAdmin,
                'totalSpv' => $totalSPV,
                'totalUser' => $totalUser,
                'totalActiveTrans' => $totalActiveTrans,
                'totalOnTime' => $totalOnTime,
                'totalOutTime' => $totalOutTime,
                'userActivities' => $userActivities,
                'dataGrafikChart' => $dataGrafikChart,
                'filter' => $filter,
            ]);
        } catch (\Exception $e) {
            Alert::error($e->getMessage(), 'error');
            return back();
        }
    }
}
