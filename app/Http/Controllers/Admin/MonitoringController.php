<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Datas;
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
            $totalActiveTrans = User::where('isActive', 1)
                ->where('position_id', '!=', 1)
                ->where('position_id', '!=', 2)
                ->count();

            $today = Carbon::today();
            $userActives = DB::table('users')
                ->join('positions', 'users.position_id', '=', 'positions.id')
                ->join('offices', 'users.office_id', '=', 'offices.id')
                ->join('user_activities', 'users.uuid', '=', 'user_activities.user_uuid')
                ->where('users.isActive', '=', 1)
                ->where('positions.id', '!=', 1)
                ->where('positions.id', '!=', 2)
                ->whereDate('user_activities.created_at', $today)
                ->select('users.uuid', 'users.name', 'users.photo', 'users.isProcessing', 'positions.name as position_name', 'offices.name as office_name', DB::raw('count(user_activities.id) as totalActivity'))
                ->groupBy('users.uuid', 'users.name', 'users.photo', 'users.isProcessing', 'positions.name', 'office_name')
                ->get();
            return view('pages.admin.monitoring.index', compact('app', 'totalActiveTrans', 'userActives'));
        } catch (\Exception $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->back();
        }
    }

    public function lastData(Request $request)
    {
        try {
            $app = Setting::first();
            $totalActiveTrans = User::where('isActive', 1)
                ->where('position_id', '!=', 1)
                ->where('position_id', '!=', 2)
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
                return view('pages.admin.monitoring.detail', compact('data', 'app', 'totalActiveTrans'));
            } else {
                Alert::toast('Data tidak ditemukan', 'error');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Alert::toast($e->getMessage(), 'error');
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
