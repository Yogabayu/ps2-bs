<?php

namespace App\Http\Controllers\Spv;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $profile = User::where('uuid', Auth::user()->uuid)->first();
            $app = Setting::first();
            $totalActiveTrans = User::where('isActive', 1)->count();

            return view('pages.spv.profile', compact('profile', 'app','totalActiveTrans'));
        } catch (\Exception $e) {
            Alert::toast($e->getMessage(), 'success');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $uuid)
    {
        try {
            $request->validate([
                'photo' => 'mimes:jpeg,jpg,png|max:2048',
            ]);
            // dd($request->all());
            $user = User::where('uuid', $uuid)->first();
            $user->nik = $request->nik;
            $user->name = $request->name;
            $user->email = $request->email;

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
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();
            UserActivity::create([
                'user_uuid' => Auth::user()->uuid,
                'activity' => 'melakukan update user : ' . $user->name,
            ]);
            Alert::toast('Berhasil melakukan update user', 'success');
            return redirect()->route('s-profile.index');
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
        //
    }
}
