<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Office;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class OfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $app = Setting::first();
            $totalActiveTrans = User::where('isActive', 1)->where('position_id','!=',1)->count();
            $datas = Office::with('supervisor')->withCount('users')->get();
            return view("pages.admin.office.index", [
                'datas' => $datas,
                'app' => $app,
                'totalActiveTrans' => $totalActiveTrans,
            ]);
        } catch (\Exception $e) {
            Alert::error($e->getMessage(), 'error');
            return redirect()->back();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $app = Setting::first();
        $users = DB::table('users')->select('uuid','name')->get();
        $totalActiveTrans = User::where('isActive', 1)->where('position_id','!=',1)->count();
        return view('pages.admin.office.action.insert',compact('app','totalActiveTrans','users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        try {
            $request->validate([
                'code' => "required",
                'name' => "required",
                'supervisor_uuid' => 'required'
            ]);

            $office = new Office();
            $office->code = $request->code;
            $office->name = $request->name;
            $office->supervisor_uuid = $request->supervisor_uuid;
            $office->save();

            UserActivity::create([
                'user_uuid' => Auth::user()->uuid,
                'activity' => 'Menambahkan kantor baru : ' . $office->name,
            ]);

            Alert::toast('Sukses menambah kantor', 'success');
            return redirect()->route('office.index');
        } catch (\Exception $e) {
            Alert::error($e->getMessage(), 'error');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $data = Office::find($id);
            $app = Setting::first();
            $totalActiveTrans = User::where('isActive', 1)->where('position_id','!=',1)->count();
            $users = DB::table('users')->select('uuid','name')->get();

            return view('pages.admin.office.action.update', compact('app','data','totalActiveTrans','users'));
        } catch (\Exception $e) {
            Alert::error($e->getMessage(), 'error');
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $office = Office::find($id);
            $office->code = $request->code;
            $office->name = $request->name;
            $office->supervisor_uuid = $request->supervisor_uuid;
            $office->save();

            UserActivity::create([
                'user_uuid' => Auth::user()->uuid,
                'activity' => 'Update kantor : ' . $office->name,
            ]);

            Alert::toast('Sukses update data kantor', 'success');
            return redirect()->route('office.index');
        } catch (\Exception $e) {
            Alert::error($e->getMessage(), 'error');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            
            $del = Office::find($id);
            UserActivity::create([
                'user_uuid' => Auth::user()->uuid,
                'activity' => 'Menghapus kantor : ' . $del->name,
            ]);
            $del->delete();

            Alert::toast('Sukses menghapus kantor', 'success');
            return redirect()->route('office.index');
        } catch (\Exception $e) {
            Alert::error($e->getMessage(), 'error');
            return redirect()->back();
        }
    }
}
