<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class AuthController extends Controller
{
    public function index()
    {

        $app = Setting::first();
        return view("welcome",[
                'app' => $app,]);
    }
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                // dd($user);
                if ($user->position_id === 1) {
                    UserActivity::create([
                        'user_uuid' => Auth::user()->uuid,
                        'activity' => 'Login ke sistem',
                    ]);
                    Alert::toast('Berhasil masuk sebagai admin', 'success');
                    return redirect()->route('indexAdmin');
                } elseif ($user->position_id == 2) {
                    Alert::toast('Error ini error dari controller', 'error');
                    abort(403, 'Unauthorized action. | spv');
                } else {
                    UserActivity::create([
                        'user_uuid' => Auth::user()->uuid,
                        'activity' => 'Login ke sistem',
                    ]);
                    Alert::toast('Berhasil masuk sebagai admin', 'success');
                    return redirect()->route('u-dashboard.index');
                }
            } else {
                Alert::toast('Email atau Password salah', 'error');
                return redirect('/')->with('error', 'Email atau Password salah');
            }
        } catch (\Exception $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect('/')->with('error', $e->getMessage());
        }
    }

    public function logout()
    {
        UserActivity::create([
            'user_uuid' => Auth::user()->uuid,
            'activity' => 'Logout sistem',
        ]);
        Auth::logout();
        return redirect('/');
    }
}
