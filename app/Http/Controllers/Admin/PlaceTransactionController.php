<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Place_transcs;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class PlaceTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $datas = Place_transcs::all();
            $app = Setting::first();
            $totalActiveTrans = User::where('isActive', 1)->where('position_id','!=',1)->count();
            return view("pages.admin.place-transc.index", compact("datas","app","totalActiveTrans"));
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
        $totalActiveTrans = User::where('isActive', 1)->where('position_id','!=',1)->count();
        return view('pages.admin.place-transc.action.insert', compact('app','totalActivetrans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'code' => "required",
                'name' => "required"
            ]);

            $place = new Place_transcs();
            $place->code = $request->code;
            $place->name = $request->name;
            $place->save();

            UserActivity::create([
                'user_uuid' => Auth::user()->uuid,
                'activity' => 'Menambahkan Tempat Transaksi baru : ' . $place->name,
            ]);

            Alert::toast('Sukses menambah kantor', 'success');
            return redirect()->route('place-transc.index');
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
            $data = Place_transcs::find($id);
            $app = Setting::first();
            $totalActiveTrans = User::where('isActive', 1)->where('position_id','!=',1)->count();

            return view('pages.admin.place-transc.action.update', compact('app','data','totalActiveTrans'));
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
            $place = Place_transcs::find($id);
            $place->code = $request->code;
            $place->name = $request->name;
            $place->save();

            UserActivity::create([
                'user_uuid' => Auth::user()->uuid,
                'activity' => 'Melakukan Update Tempat Transaksi : ' . $place->name,
            ]);

            Alert::toast('Sukses update data kantor', 'success');
            return redirect()->route('place-transc.index');
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

            $del = Place_transcs::find($id);
            UserActivity::create([
                'user_uuid' => Auth::user()->uuid,
                'activity' => 'Menghapus Tempat Transaksi : ' . $del->name,
            ]);
            $del->delete();

            Alert::toast('Sukses menghapus kantor', 'success');
            return redirect()->route('place-transc.index');
        } catch (\Exception $e) {
            Alert::error($e->getMessage(), 'error');
            return redirect()->back();
        }
    }
}
