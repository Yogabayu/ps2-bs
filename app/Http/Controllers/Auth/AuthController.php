<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function forgotaction(Request $request)
    {
        try {
            // dd($request->all());
            $request->validate([
                'email' => 'required|email',
            ]);

            $cekEmail = User::where('email', $request->email)->first();
            if (!$cekEmail) {
                Alert::toast('email tidak ditemukan', 'error');
                return redirect('forgot-password');
            }
            $cekEmail->token = Str::random(40);
            $cekEmail->token_expires_at = Carbon::now()->addMinutes(30);
            $cekEmail->save();

            $this->sendForgotEmail($cekEmail);

            Alert::toast('Link reset berhasil dibuat, silahkan cek inbox/spam di email anda', 'success');
            return redirect('forgot-password');
        } catch (\Exception $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect('forgot-password');
        }
    }

    private function sendForgotEmail(User $user)
    {
        $verificationLink = route('forgot-email', ['token' => $user->token]);

        Mail::send('pages.email.forgot', ['user' => $user, 'verificationLink' => $verificationLink], function ($message) use ($user) {
            $message->from('pengaduan@bankarthaya.com', 'Administrator');
            $message->to($user->email, 'Admin');
            $message->subject('Forgot Email');
        });
    }

    public function verifyForgot($token)
    {
        $user = User::where('token', $token)->first();

        if (!$user) {
            Alert::toast('Token Tidak Valid', 'error');
            return redirect('/');
        }

        $expiresAt = Carbon::parse($user->token_expires_at);

        
        if ($expiresAt->lt(Carbon::now()) || $expiresAt->gt(Carbon::now()->addMinutes(30))) {
            Alert::toast('Token Sudah Kadaluarsa', 'error');
            return redirect('/');
        }

        $user->token = null;
        $user->token_expires_at = null;
        $user->password = Hash::make('12345678');
        $user->save();

        Alert::toast('Berhasil ! selamat reset password berhasil', 'success');
        return redirect('/');
    }

    public function forgot_password()
    {
        $app = Setting::first();
        return view('pages.auth.forgot-password', compact('app'));
    }
    public function index()
    {
        $app = Setting::first();
        return view("welcome", [
            'app' => $app,
        ]);
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
                // dd($user->position_id);
                if ($user->position_id === 1) {
                    UserActivity::create([
                        'user_uuid' => Auth::user()->uuid,
                        'activity' => 'Login ke sistem',
                    ]);

                    //update active
                    User::where('uuid', Auth::user()->uuid)->update(['isActive' => 1]);

                    Alert::toast('Berhasil masuk sebagai admin', 'success');
                    return redirect()->route('indexAdmin');
                } elseif ($user->position_id == 2) {
                    //update active
                    User::where('uuid', Auth::user()->uuid)->update(['isActive' => 1]);
                    UserActivity::create([
                        'user_uuid' => Auth::user()->uuid,
                        'activity' => 'Login ke sistem',
                    ]);
                    Alert::toast('Berhasil masuk sebagai SPV', 'success');
                    return redirect()->route('s-dashboard');
                } else {
                    //update active
                    User::where('uuid', Auth::user()->uuid)->update(['isActive' => 1]);
                    UserActivity::create([
                        'user_uuid' => Auth::user()->uuid,
                        'activity' => 'Login ke sistem',
                    ]);
                    Alert::toast('Berhasil masuk sebagai user', 'success');
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
        User::where('uuid', Auth::user()->uuid)->update(['isActive' => 0]);
        Auth::logout();
        return redirect('/');
    }
}
