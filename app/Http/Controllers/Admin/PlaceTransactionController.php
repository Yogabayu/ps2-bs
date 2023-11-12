<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Place_transcs;
use Illuminate\Http\Request;
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
            return view("pages.admin.place-transc.index", [
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

            $office = new Place_transcs();
            $office->code = $request->code;
            $office->name = $request->name;
            $office->save();

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
            $office = Place_transcs::find($id);
            $office->code = $request->code;
            $office->name = $request->name;
            $office->save();

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
            $del->delete();

            Alert::toast('Sukses menghapus kantor', 'success');
            return redirect()->route('place-transc.index');
        } catch (\Exception $e) {
            Alert::error($e->getMessage(), 'error');
            return redirect()->back();
        }
    }
}
