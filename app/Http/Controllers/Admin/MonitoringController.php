<?php

namespace App\Http\Controllers\Admin;

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
        //URUNG kurang nampilne
        try {
            $app = Setting::first();
            $totalActiveTrans = User::where('isActive', 1)->count();
            $userActives = DB::table('users')
            ->join('positions', 'users.position_id', '=', 'positions.id')
            ->join('user_activities', 'users.uuid', '=', 'user_activities.user_uuid')
            ->where('users.isActive', '=', 1)
            ->where('positions.id', '!=', 1)
            ->whereDate('user_activities.created_at', now()) // Filter aktivitas hanya pada hari ini
            ->select('users.id', 'users.name', 'users.photo', 'positions.name as position_name', DB::raw('count(*) as
            totalActivity'))
            ->groupBy('users.id', 'users.name', 'users.photo', 'positions.name')
            ->get();


            dd($userActives);

            return view('pages.admin.monitoring.index', compact('app', 'totalActiveTrans', 'userActives'));
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
