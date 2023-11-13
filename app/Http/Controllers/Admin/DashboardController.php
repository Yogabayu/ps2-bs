<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Datas;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $app = Setting::first();
            $totalAdmin = User::where('position_id', 1)->count();
            $totalSPV = User::where('position_id', 2)->count();
            $totalUser = User::whereNotIn('position_id', [1, 2])->count();
            $totalActiveTrans = Datas::where('isActive', 1)->count();
            $totalOnTime = Datas::where('result', 1)->count();
            $totalOutTime = Datas::where('result', 0)->count();
            $userActivities = UserActivity::with('user')->limit(5)->orderBy('id','DESC')->get();

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
            ]);
        } catch (\Exception $e) {
            Alert::error($e->getMessage(), 'error');
            return back();
        }
    }
}
