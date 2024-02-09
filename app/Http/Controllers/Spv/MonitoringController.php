<?php

namespace App\Http\Controllers\Spv;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class MonitoringController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $app = Setting::first();

            $today = Carbon::today();
            $totalActiveTrans = DB::table('subordinates as s')
                ->join('users as u', 's.subordinate_uuid', '=', 'u.uuid')
                ->where('u.isActive', '=', 1)
                ->where('u.position_id', '!=', 1)
                ->where('u.position_id', '!=', 2)
                ->where('s.supervisor_id', Auth::user()->uuid)
                ->count();
            $userActives = DB::table('subordinates as s')
                ->join('users as u', 's.subordinate_uuid', '=', 'u.uuid')
                ->join('positions as p', 'p.id', '=', 'u.position_id')
                ->join('offices as o', 'o.id', '=', 'u.office_id')
                ->join('user_activities as ua', 'u.uuid', '=', 'ua.user_uuid')
                ->where('s.supervisor_id', Auth::user()->uuid)
                ->where('u.isActive', '=', 1)
                ->where('p.id', '!=', 1)
                ->where('p.id', '!=', 2)
                ->whereDate('ua.created_at', '=', now())
                ->select('u.uuid', 'u.name', 'u.photo', 'u.isProcessing', 'p.name as position_name', 'o.name as office_name', DB::raw('count(ua.id) as totalActivity'))
                ->groupBy('u.uuid', 'u.name', 'u.photo', 'u.isProcessing', 'p.name', 'office_name')
                ->get();

            return view('pages.spv.monitoring.index', compact('app', 'totalActiveTrans', 'userActives'));
        } catch (\Exception $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->back();
        }
    }

    public function lastData(Request $request)
    {
        try {
            $app = Setting::first();
            $totalActiveTrans = DB::table('subordinates as s')
                ->join('users as u', 's.subordinate_uuid', '=', 'u.uuid')
                ->where('u.isActive', '=', 1)
                ->where('u.position_id', '!=', 1)
                ->where('u.position_id', '!=', 2)
                ->where('s.supervisor_id', Auth::user()->uuid)
                ->count();
            $data = DB::table('datas')
                ->join('users', 'users.uuid', '=', 'datas.user_uuid')
                ->join('transactions', 'transactions.id', '=', 'datas.transc_id')
                ->join('place_transcs', 'place_transcs.id', '=', 'datas.place_transc_id')
                ->where('datas.user_uuid', $request->last_uuid)
                ->select(
                    'datas.*',
                    'users.name as username',
                    'transactions.name as transname',
                    'transactions.code as transcode',
                    'transactions.max_time as transMaxTime',
                    'place_transcs.name as placename',
                    'place_transcs.code as placecode',
                    DB::raw('SEC_TO_TIME(TIMESTAMPDIFF(SECOND, datas.start, datas.end)) as lamaTransaksi'),
                )
                ->orderBy('created_at', 'desc')
                ->first();


            if ($data) {
                return view('pages.spv.monitoring.detail', compact('data', 'app', 'totalActiveTrans'));
            } else {
                Alert::toast('Data tidak ditemukan', 'error');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->back();
        }
    }
}
