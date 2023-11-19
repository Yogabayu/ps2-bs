<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Office;
use App\Models\Setting;
use App\Models\Subordinate;
use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class SubordinateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $app = Setting::first();
            $totalActiveTrans = User::where('isActive', 1)->where('position_id','!=',1)->count();

            $offices = Office::with('supervisor')->get();

            return view('pages.admin.subordinate.index', compact('app', 'totalActiveTrans', 'offices'));
        } catch (\Exception $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->back();
        }
    }

    public function detail(Request $request)
    {
        try {
            $app = Setting::first();
            $totalActiveTrans = User::where('isActive', 1)->where('position_id','!=',1)->count();
            $office = Office::findOrFail($request->office_id);
            $spv_uuid = $office->supervisor_uuid;
            $spv_data = DB::table('offices')
                ->join('users', 'offices.supervisor_uuid', '=', 'users.uuid')
                ->where('offices.supervisor_uuid', '=', $spv_uuid)
                ->select('offices.id as id_office', 'offices.name as name_office', 'users.*')
                ->first();
            $subordinates = DB::table('users')
                ->join('offices', 'users.office_id', '=', 'offices.id')
                ->join('subordinates', 'users.uuid', '=', 'subordinates.subordinate_uuid')
                ->join('positions', 'users.position_id', '=', 'positions.id')
                ->select('users.*', 'offices.code as office_code', 'offices.name as office_name', 'positions.name as position_name', 'subordinates.id as id_sub')
                ->where('offices.supervisor_uuid', '=', $spv_uuid)
                ->get();
            $users = DB::table('users')
                ->join('positions', 'users.position_id', '=', 'positions.id')
                ->select('users.id', 'users.uuid', 'users.name')
                ->where('users.position_id', '!=', 1)
                ->get();

            return view('pages.admin.subordinate.detail', compact('app', 'totalActiveTrans', 'subordinates', 'spv_data', 'office', 'users'));
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
        try {
            $request->validate([
                'office_id' => 'required',
                'spv_uuid' => 'required',
                'user_uuid' => 'required'
            ]);

            $cekDataSub = Subordinate::where('subordinate_uuid', '=', $request->user_uuid)->count();
            $cekDataSpv = Subordinate::where('supervisor_id', '=', $request->user_uuid)->count();
            $cekDataSpv2 = Office::where('supervisor_uuid', '=', $request->user_uuid)->count();
            if ($cekDataSub > 0) {
                Alert::toast('User sudah terdaftar sebagai anggota dari salah satu spv', 'error');
                return redirect()->back();
            } elseif ($cekDataSpv > 0 || $cekDataSpv2 > 0) {
                Alert::toast('User sudah terdaftar sebagai spv, silahkan ubah posisi user terlebih dahulu', 'error');
                return redirect()->back();
            } else {
                $add = new Subordinate();
                $add->supervisor_id = $request->spv_uuid;
                $add->subordinate_uuid = $request->user_uuid;
                $add->save();

                UserActivity::create([
                    'user_uuid' => Auth::user()->uuid,
                    'activity' => 'Melakukan tambah Subordinate : ' . $add->subordinate_uuid,
                ]);

                Alert::toast('Berhasil menambah data', 'success');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Alert::toast($e->getMessage(), 'error');
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
            $subordinate = Subordinate::findorFail($id);

            UserActivity::create([
                'user_uuid' => Auth::user()->uuid,
                'activity' => 'Melakukan hapus subordinate',
            ]);

            $subordinate->delete();

            Alert::toast('Berhasil Menghapus data', 'success');
            return redirect()->back();
        } catch (\Exception $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->back();
        }
    }
}
