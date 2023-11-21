<?php

namespace App\Http\Controllers\Spv;

use App\Http\Controllers\Controller;
use App\Models\Office;
use App\Models\Position;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class ListUserController extends Controller
{
    public function rstpwd(Request $request)
    {
        try {
            $request->validate([
                'uuid' => 'required',
            ]);

            $user = User::where('uuid', $request->uuid)->first();

            if (!$user) {
                Alert::toast('User not found', 'error');
                return redirect()->back();
            }

            $user->password = Hash::make('12345678');
            $user->save();

            UserActivity::create([
                'user_uuid' => Auth::user()->uuid,
                'activity' => 'Menambahkan user baru : ' . $user->name,
            ]);

            Alert::toast('Berhasil mereset password', 'success');
            return redirect()->back();
        } catch (\Exception $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->back();
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $datas = DB::table('subordinates as s')
                ->join('users as u', 's.subordinate_uuid', '=', 'u.uuid')
                ->join('positions as p', 'p.id', '=', 'u.position_id')
                ->join('offices as o', 'o.id', '=', 'u.office_id')
                ->where('s.supervisor_id', Auth::user()->uuid)
                ->select('u.*', 'p.name as namePosition', 'o.name as nameOffice', 'o.code as codeOffice')
                ->distinct()
                ->get();
            $app = Setting::first();
            $totalActiveTrans = DB::table('subordinates as s')
                ->join('users as u', 's.subordinate_uuid', '=', 'u.uuid')
                ->where('u.isActive','=',1)
                ->where('u.position_id','!=',1)
                ->where('s.supervisor_id', Auth::user()->uuid)
                ->count();
            $offices = Office::all();
            $positions = Position::all();

            return view("pages.spv.listuser.index", [
                'type_menu' => 'user',
                'datas' => $datas,
                'app' => $app,
                'offices' => $offices,
                'positions' => $positions,
                'totalActiveTrans' => $totalActiveTrans,
            ]);
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
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $uuid)
    {
        try {
            $request->validate([
                'photo'         => 'mimes:jpeg,jpg,png|max:2048',
            ]);
            $user = User::where('uuid', $uuid)->first();
            $user->nik = $request->nik;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->office_id = $request->office_id;
            $user->position_id = $request->position_id;

            if ($request->hasFile('photo')) {
                $oldImage = $user->photo;
                if ($oldImage && File::exists(public_path('file/profile/' . $oldImage))) {
                    File::delete(public_path('file/profile/' . $oldImage));
                }

                $imageEXT = $request->file('photo')->getClientOriginalName();
                $filename = pathinfo($imageEXT, PATHINFO_FILENAME);
                $EXT = $request->file('photo')->getClientOriginalExtension();
                $fileimage = $filename . '_' . time() . '.' . $EXT;
                $path = $request->file('photo')->move(public_path('file/profile'), $fileimage);
                $user->photo = $fileimage;
            }
            if ($request->has('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();
            UserActivity::create([
                'user_uuid' => Auth::user()->uuid,
                'activity' => 'melakukan update user : ' . $user->name,
            ]);
            Alert::toast('Berhasil melakukan update user', 'success');
            return redirect()->route('s-listuser.index');
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
            $user = User::findOrFail($id);

            UserActivity::create([
                'user_uuid' => Auth::user()->uuid,
                'activity' => 'Melakukan hapus user : ' . $user->name,
            ]);

            if (!empty($user->photo)) {
                if ($user->photo && File::exists(public_path('file/profile/' . $user->photo))) {
                    File::delete(public_path('file/profile/' . $user->photo));
                }
            }
            $user->delete();

            Alert::toast('User berhasil di hapus', 'success');
            return redirect()->back();
        } catch (QueryException $e) {
            Alert::error('Gagal Menghapus data, karena user memiliki data yang masih tertaut di aplikasi ', 'error');
            return redirect()->back();
        } catch (\Exception $e) {
            Alert::error($e->getMessage(), 'error');
            return redirect()->back();
        }
    }
}
