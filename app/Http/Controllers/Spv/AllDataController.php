<?php

namespace App\Http\Controllers\Spv;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class AllDataController extends Controller
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
            $datas = DB::table('subordinates as s')
                ->join('users as u', 's.subordinate_uuid', '=', 'u.uuid')
                ->join('positions as p', 'p.id', '=', 'u.position_id')
                ->join('offices as o', 'o.id', '=', 'u.office_id')
                ->join('datas as d', 'd.user_uuid', '=', 'u.uuid')
                ->join('transactions as t','t.id','=','d.transc_id')
                ->join('place_transcs as pt','pt.id','=','d.place_transc_id')
                ->where('s.supervisor_id', Auth::user()->uuid)
                ->select('d.*','u.name as username', 'p.name as namePosition', 'o.name as nameOffice', 'o.code as codeOffice','t.code as codeTransaction','t.name as nameTransaction','t.max_time as maxTimeTrans','pt.code as ptcode','pt.name as ptname')
                ->orderBy('d.created_at', 'desc')
                ->distinct()
                ->get();

            return view('pages.spv.all-data.index',compact('app','totalActiveTrans','datas'));
        } catch (\Exception $e) {
            Alert::toast();
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
