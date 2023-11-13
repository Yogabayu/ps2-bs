<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Office;
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
            $datas = Office::withCount('users')->get();
            return view("pages.admin.position.index", [
                'datas' => $datas,
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
        return view('pages.admin.office.action.insert');
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

            $office = new Office();
            $office->code = $request->code;
            $office->name = $request->name;
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

            return view('pages.admin.office.action.update', [
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
            $office = Office::find($id);
            $office->code = $request->code;
            $office->name = $request->name;
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
