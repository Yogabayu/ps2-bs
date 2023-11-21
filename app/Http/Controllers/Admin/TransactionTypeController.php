<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Position;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class TransactionTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $app = Setting::first();
            $datas = Transaction::with('position')->get();
            $totalActiveTrans = User::where('isActive', 1)
                ->where('position_id', '!=', 1)
                ->where('position_id', '!=', 2)
                ->count();
            return view("pages.admin.transc-type.index", compact("app","datas","totalActiveTrans"));
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
        try {
            $positions = Position::whereNotIn('id', [1, 2])->get();
            $app = Setting::first();
            $totalActiveTrans = User::where('isActive', 1)
                ->where('position_id', '!=', 1)
                ->where('position_id', '!=', 2)
                ->count();
            return view('pages.admin.transc-type.action.insert', compact('app','positions','totalActiveTrans'));
        } catch (\Exception $e) {
            Alert::error($e->getMessage(), 'error');
            return redirect()->back();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'position_id' => "required",
                'code' => "required",
                'name' => "required",
                'max_time' => "required"
            ]);

            $tipe               = new Transaction();
            $tipe->position_id  = $request->position_id;
            $tipe->code         = $request->code;
            $tipe->name         = $request->name;
            $tipe->max_time     = $request->max_time;
            $tipe->save();

            UserActivity::create([
                'user_uuid' => Auth::user()->uuid,
                'activity' => 'Menambahkan transaksi baru : ' . $tipe->name,
            ]);

            Alert::toast('Sukses menambah Transaksi baru ', 'success');
            return redirect()->route('transc-type.index');
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
            $positions = Position::whereNotIn('id', [1, 2])->get();
            $type = Transaction::find($id);
            $app = Setting::first();
            $totalActiveTrans = User::where('isActive', 1)
                ->where('position_id', '!=', 1)
                ->where('position_id', '!=', 2)
                ->count();

            return view('pages.admin.transc-type.action.update', compact('positions','app','type','totalActiveTrans'));
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
            $request->validate([
                'position_id' => "required",
                'code' => "required",
                'name' => "required",
                'max_time' => "required"
            ]);

            $tipe = Transaction::find($id);
            $tipe->position_id = $request->position_id;
            $tipe->code = $request->code;
            $tipe->name = $request->name;
            $tipe->max_time = $request->max_time;
            $tipe->save();

            UserActivity::create([
                'user_uuid' => Auth::user()->uuid,
                'activity' => 'Update tipe transaksi : ' . $tipe->name,
            ]);

            Alert::toast('Sukses update data transaksi', 'success');
            return redirect()->route('transc-type.index');
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
            $del = Transaction::find($id);
            UserActivity::create([
                'user_uuid' => Auth::user()->uuid,
                'activity' => 'Menghapus tipe transaksi : ' . $del->name,
            ]);
            $del->delete();

            Alert::toast('Sukses menghapus tipe transaksi', 'success');
            return redirect()->route('transc-type.index');
        } catch (\Exception $e) {
            Alert::error($e->getMessage(), 'error');
            return redirect()->back();
        }
    }
}
