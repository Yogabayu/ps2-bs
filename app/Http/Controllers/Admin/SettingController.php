<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

class SettingController extends Controller
{
    public function index()
    {
        try {
            $app = Setting::first();
            $totalActiveTrans = User::where('isActive', 1)->where('position_id','!=',1)->count();
            return view("pages.admin.setting.index", compact("app","totalActiveTrans"));
        } catch (\Exception $e) {
            Alert::error($e->getMessage(), 'error');
            return redirect()->back();
        }
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
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
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $setting = Setting::find($id);
            $setting->name_app = $request->name_app;

            if ($request->hasFile('logo')) {
                $oldImage = $setting->logo;
                if ($oldImage && File::exists(public_path('file/setting/' . $oldImage))) {
                    File::delete(public_path('file/setting/' . $oldImage));
                }

                $imageEXT = $request->file('logo')->getClientOriginalName();
                $filename = pathinfo($imageEXT, PATHINFO_FILENAME);
                $EXT = $request->file('logo')->getClientOriginalExtension();
                $fileimage = $filename . '_' . time() . '.' . $EXT;
                $path = $request->file('logo')->move(public_path('file/setting'), $fileimage);
                $setting->logo = $fileimage;
            }
            $setting->save();

            UserActivity::create([
                'user_uuid' => Auth::user()->uuid,
                'activity' => 'Melakukan Update setting',
            ]);

            Alert::toast('Sukses update data setting', 'success');
            return redirect()->route('setting-app.index');
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
    }
}
