<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Position;
use App\Models\Setting;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $datas = Position::whereNotIn('id', [1])->get();
            $app = Setting::first();
            return view("pages.admin.position.index", [
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
        return view('pages.admin.position.action.insert');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => "required"
            ]);

            $position = new Position();
            $position->name = $request->name;
            $position->save();

            UserActivity::create([
                'user_uuid' => Auth::user()->uuid,
                'activity' => 'Menambahkan Posisi baru : ' . $position->name,
            ]);

            Alert::toast('Sukses menambah posisi', 'success');
            return redirect()->route('position.index');
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $data = Position::find($id);

            return view('pages.admin.position.action.update', [
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
    public function update(Request $request, string $id)
    {
        try {
            $position = Position::find($id);
            $position->name = $request->name;
            $position->save();

            UserActivity::create([
                'user_uuid' => Auth::user()->uuid,
                'activity' => 'Menambahkan Posisi baru : ' . $position->name,
            ]);

            Alert::toast('Sukses update data posisi', 'success');
            return redirect()->route('position.index');
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
            $del = Position::find($id);

            UserActivity::create([
                'user_uuid' => Auth::user()->uuid,
                'activity' => 'Menghapus Posisi baru : ' . $del->name,
            ]);
            
            $del->delete();

            Alert::toast('Sukses menghapus posisi', 'success');
            return redirect()->route('position.index');
        } catch (\Exception $e) {
            Alert::error($e->getMessage(), 'error');
            return redirect()->back();
        }
    }
}
