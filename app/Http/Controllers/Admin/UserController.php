<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Office;
use App\Models\Position;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $datas = User::with('position', 'office')->get();
            $app = Setting::first();
            $totalActiveTrans = User::where('isActive', 1)->count();

            return view("pages.admin.user.index", [
                'type_menu' => 'user',
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
        $offices = Office::all();
        $positions = Position::all();
        $app = Setting::first();
        $totalActiveTrans = User::where('isActive', 1)->count();
        return view('pages.admin.user.action.insert', [
            'offices' => $offices,
            'positions' => $positions,
            'app' => $app,
            'totalActiveTrans' => $totalActiveTrans,
        ]);
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nik' => 'required|unique:users',
                'name' => 'required',
                'email' => 'required|unique:users',
                'office_id' => 'required',
                'position_id' => 'required',
                'password' => 'required',
                'photo' => 'required|mimes:jpeg,jpg,png|max:2048',
            ]);

            $user               = new User();
            $user->uuid         = Str::uuid();
            $user->nik          = $request->nik;
            $user->name         = $request->name;
            $user->email        = $request->email;
            $user->office_id    = $request->office_id;
            $user->position_id  = $request->position_id;
            $user->password     = Hash::make($request->password);
            
            if ($request->hasFile('photo')) {
                $imageEXT = $request->file('photo')->getClientOriginalName();
                $filename = pathinfo($imageEXT, PATHINFO_FILENAME);
                $EXT = $request->file('photo')->getClientOriginalExtension();
                $fileimage = $filename . '_' . time() . '.' . $EXT;
                $path = $request->file('photo')->move(public_path('file/profile'), $fileimage);
                $user->photo = $fileimage;
            }
            
            $user->save();
            
            UserActivity::create([
                'user_uuid' => Auth::user()->uuid,
                'activity' => 'Menambahkan user baru : ' . $user->name,
            ]);
            
            Alert::toast('Berhasil Menambahkan user baru', 'success');
            return redirect()->route('user.index');
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
        try {
            $user = User::find($id);
            $offices = Office::all();
            $positions = Position::all();
            $app = Setting::first();
            $totalActiveTrans = User::where('isActive', 1)->count();
            
            return view('pages.admin.user.action.update', [
                'user'      => $user,
                'offices'   => $offices,
                'positions' => $positions,
                'app'       => $app,
                'totalActiveTrans' => $totalActiveTrans,
            ]);
        } catch (\Exception $e) {
            Alert::error($e->getMessage(), 'error');
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($uuid)
    {
        //
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
            return redirect()->route('user.index');
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
        } catch (\Exception $e) {
            Alert::error($e->getMessage(), 'error');
            return redirect()->back();
        }
    }
}
