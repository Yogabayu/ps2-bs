<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Place_transcs;
use App\Models\Setting;
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
            return view("pages.admin.place-transc.index", [
                'datas' => $datas,
                'app' => $app,
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
        return view('pages.admin.place-transc.action.insert');
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

            return view('pages.admin.place-transc.action.update', [
                'data' => $data
            ]);
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
