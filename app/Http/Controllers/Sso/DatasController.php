<?php

namespace App\Http\Controllers\Sso;

use App\Http\Controllers\Controller;
use App\Models\Sso;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DatasController extends Controller
{

    public function getUserData(Request $request)
    {
        try {
            // Validate the incoming request
            $request->validate([
                'session_token' => 'required|string',
            ]);

            // Retrieve the session token from the request
            $providerToken = $request->session_token;

            // Look up the SSO record based on the provided session token
            $sso = Sso::where('session_token', $providerToken)->first();

            if (!$sso) {
                return response()->json([
                    'error' => true,
                    'message' => 'Token not found',
                ]);
            }

            // Check if the token is within the valid time range
            $dateNow = Carbon::now();

            if ($dateNow->between($sso->start, $sso->end)) {
                // Implement your data retrieval logic here
                $data = DB::table('users')
                    ->where('position_id', '!=', 1)
                    ->select('users.*')
                    ->get();

                return response()->json([
                    'error' => false,
                    'data' => $data,
                    'message' => 'berhasil mendapatkan data',
                ]);
            } else {
                return response()->json([
                    'error' => true,
                    'message' => 'Token tidak aktif',
                ]);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'error' => true,
                'message' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
