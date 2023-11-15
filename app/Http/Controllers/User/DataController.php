<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Datas;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\UserActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

class DataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $app = Setting::first();
            $datas =
                Datas::with('user.position', 'transaction', 'placeTransc')->where('user_uuid', Auth::user()->uuid)->orderBy('created_at', 'desc')->get();

            return view('pages.user.all-data', compact('app', 'datas'));
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                "user_uuid" => "required",
                "transc_id" => "required",
                "place_transc_id" => "required",
                "date" => "required",
                "start" => "required",
                "end" => "required",
                "nominal" => "required",
                "customer_name" => "required",
                "evidence_file" => "required",
            ]);

            //calculate result
            $startDateTime = Carbon::parse($request->date . ' ' . $request->start);
            $endDateTime = Carbon::parse($request->date . ' ' . $request->end);

            $timeDifferenceInSeconds = $startDateTime->diffInSeconds($endDateTime);

            $trans = Transaction::find($request->transc_id);
            $maxTimeInSeconds = Carbon::parse($trans->max_time)->diffInSeconds(Carbon::parse('00:00:00'));

            //send data
            $data                   = new Datas();
            $data->user_uuid        = $request->user_uuid;
            $data->transc_id        = $request->transc_id;
            $data->place_transc_id  = $request->place_transc_id;
            $data->date             = $request->date;
            $data->start            = $request->start;
            $data->end              = $request->end;
            $data->nominal          = preg_replace("/[^0-9]/", "", $request->nominal);;
            $data->customer_name    = $request->customer_name;
            $data->isActive         = 0;

            //upload file
            $imageEXT = $request->file('evidence_file')->getClientOriginalName();
            $filename = pathinfo($imageEXT, PATHINFO_FILENAME);
            $EXT = $request->file('evidence_file')->getClientOriginalExtension();
            $fileimage = $filename . '_' . time() . '.' . $EXT;
            $path = $request->file('evidence_file')->move(public_path('file/datas'), $fileimage);
            $data->evidence_file = $fileimage;

            //calculate result
            if ($timeDifferenceInSeconds > $maxTimeInSeconds) {
                $data->result = 0;
            } else {
                $data->result = 1;
            }

            $data->save();
            UserActivity::create([
                'user_uuid' => Auth::user()->uuid,
                'activity' => 'Melakukan Tambah data, ID : '. $data->id,
            ]);
            return response()->json([
                'message' => 'Data berhasil disimpan',
                'success' => true,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'success' => false,
            ]);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $del = Datas::findOrFail($id);

            UserActivity::create([
                'user_uuid' => Auth::user()->uuid,
                'activity' => 'Melakukan hapus data : '.$del->customer_name,
            ]);

            if (!empty($del->evidence_file)) {
                if ($del->evidence_file && File::exists(public_path('file/datas/' . $del->evidence_file))) {
                    File::delete(public_path('file/datas/' . $del->evidence_file));
                }
            }
            $del->delete();

            Alert::toast('Data Berhasil dihapus', 'success');
            return redirect()->back();
        } catch (\Exception $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->back();
        }
    }
}
