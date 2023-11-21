<?php

namespace App\Http\Controllers\Spv;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
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
            $totalActiveTrans = DB::table('subordinates as s')
                ->join('users as u', 's.subordinate_uuid', '=', 'u.uuid')
                ->where('u.isActive','=',1)
                ->where('u.position_id','!=',1)
                ->where('s.supervisor_id', Auth::user()->uuid)
                ->count();
            $userActives = DB::table('subordinates as s')
                ->join('users as u', 's.subordinate_uuid', '=', 'u.uuid')
                ->join('datas as d', 'd.user_uuid', '=', 'u.uuid')
                ->join('positions as p', 'p.id', '=', 'u.position_id')
                ->join('offices as o', 'o.id', '=', 'u.office_id')
                ->where('s.supervisor_id', Auth::user()->uuid)
                ->where('u.isActive', '=', 1)
                ->where('p.id', '!=', 1)
                ->where('p.id', '!=', 2)
                ->whereDate('d.created_at', '=', now())
                ->select('u.uuid', 'u.name', 'u.photo', 'u.isProcessing', 'p.name as position_name', 'o.name as office_name', DB::raw('count(d.id) as totalActivity'))
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
                ->where('u.isActive','=',1)
                ->where('u.position_id','!=',1)
                ->where('s.supervisor_id', Auth::user()->uuid)
                ->count();
            $data = DB::table('datas')
                ->join('users', 'users.uuid', '=', 'datas.user_uuid')
                ->join('transactions', 'transactions.id', '=', 'datas.transc_id')
                ->join('place_transcs', 'place_transcs.id', '=', 'datas.place_transc_id')
                ->where('datas.user_uuid', $request->last_uuid)
                ->select('datas.*', 'users.name as username', 'transactions.name as transname', 'transactions.code as transcode', 'place_transcs.name as placename', 'place_transcs.code as placecode')
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