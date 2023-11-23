<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Sso;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;

class SsoController extends Controller
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
            $users = User::all();
            $datas = DB::table('sso')
                ->join('users','users.uuid','=','sso.user_uuid')
                ->select('sso.*','users.name as username')
                ->get();
            return view('pages.admin.sso.index',compact('app','totalActiveTrans','datas','users'));
        } catch (\Exception $e) {
            Alert::toast($e->getMessage(),'error');
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
        try {
            $request->validate([
                'user_uuid' => 'required',
                'start' => 'required|date',
                'end' => 'required|date',
            ]);

            $sso = new Sso();
            $sso->user_uuid = $request->user_uuid;
            $sso->session_token = Hash::make(Str::random(40));
            $sso->start = $request->start;
            $sso->end = $request->end;
            $sso->save();

            Alert::toast('Sukses menambahkan data','success');
            return redirect()->back();
        } catch (\Exception $e) {
            Alert::toast($e->getMessage(),'error');
            return redirect()->back();
        }
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
    public function destroy($id)
    {
        try {
            $sso = Sso::findOrFail($id);
            $sso->delete();

            Alert::toast('Berhasil menghapus data','success');
            return redirect()->back();
        } catch (\Exception $e) {
            Alert::toast($e->getMessage(),'error');
            return redirect()->back();
        }
    }
}
