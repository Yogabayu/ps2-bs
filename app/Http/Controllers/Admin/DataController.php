<?php

namespace App\Http\Controllers\Admin;

use App\Exports\DatasExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DataRequest;
use App\Models\Datas;
use App\Models\Place_transcs;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserActivity;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class DataController extends Controller
{
    public function export(Request $request)
    {
        try {
            if ($request->type == 1) {
                $data = DB::table('datas as d')
                    ->join('users as u', 'u.uuid', '=', 'd.user_uuid')
                    ->join('transactions as t', 't.id', '=', 'd.transc_id')
                    ->join('place_transcs as pt', 'pt.id', '=', 'd.place_transc_id')
                    ->join('positions as p', 'p.id', '=', 'u.position_id')
                    ->join('offices as o', 'o.id', '=', 'u.office_id')
                    ->select(
                        'd.*',
                        'u.name as username',
                        'p.name as positionName',
                        'o.name as officeName',
                        't.code as transactionCode',
                        't.name as transactionName',
                        't.max_time as transactionMaxTime',
                        'pt.code as ptCode',
                        'pt.name as ptName',
                        DB::raw('MONTH(d.date) as blnTransaksi'),
                        DB::raw('SEC_TO_TIME(TIMESTAMPDIFF(SECOND, d.start, d.end)) as lamaTransaksi'),
                        DB::raw('CASE WHEN d.result = 1 THEN "Sesuai" ELSE "Tidak Sesuai" END as timeline'),
                    )
                    ->orderBy('d.id', 'desc')
                    ->get();

                $filename = 'semua data ' . Carbon::now()->format('Y-m-d') . '.xlsx';

                UserActivity::create([
                    'user_uuid' => Auth::user()->uuid,
                    'activity' => 'Melakukan export excel : ' . $filename,
                ]);

                return Excel::download(new DatasExport($data), $filename);
            } else {
                $data = DB::table('datas as d')
                    ->join('users as u', 'u.uuid', '=', 'd.user_uuid')
                    ->join('transactions as t', 't.id', '=', 'd.transc_id')
                    ->join('place_transcs as pt', 'pt.id', '=', 'd.place_transc_id')
                    ->join('positions as p', 'p.id', '=', 'u.position_id')
                    ->join('offices as o', 'o.id', '=', 'u.office_id')
                    ->select(
                        'd.*',
                        'u.name as username',
                        'p.name as positionName',
                        'o.name as officeName',
                        't.code as transactionCode',
                        't.name as transactionName',
                        't.max_time as transactionMaxTime',
                        'pt.code as ptCode',
                        'pt.name as ptName',
                        DB::raw('MONTH(d.date) as blnTransaksi'),
                        DB::raw('SEC_TO_TIME(TIMESTAMPDIFF(SECOND, d.start, d.end)) as lamaTransaksi'),
                        DB::raw('CASE WHEN d.result = 1 THEN "Sesuai" ELSE "Tidak Sesuai" END as timeline'),
                    )
                    ->orderBy('d.id', 'desc')
                    ->get();

                $filename = 'semua data ' . Carbon::now()->format('Y-m-d') . '.pdf';
                $pdf = Pdf::loadView('pages.export.exportAll', ['data' => $data])->setPaper('legal', 'landscape');

                UserActivity::create([
                    'user_uuid' => Auth::user()->uuid,
                    'activity' => 'Melakukan export pdf : ' . $filename,
                ]);
                return $pdf->download($filename);
            }
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
            $app = Setting::first();
            $totalActiveTrans = User::where('isActive', 1)
                ->where('position_id', '!=', 1)
                ->where('position_id', '!=', 2)
                ->count();
            $datas =
                Datas::with('user', 'transaction', 'placeTransc')->orderBy('created_at', 'desc')->get();

            return view("pages.admin.all-data.index", compact("app", "datas", "totalActiveTrans"));
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
        try {
            $app = Setting::first();
            $totalActiveTrans = User::where('isActive', 1)
                ->where('position_id', '!=', 1)
                ->where('position_id', '!=', 2)
                ->count();
            $transactions = Transaction::all();
            $places = Place_transcs::all();

            return view('pages.admin.all-data.insert',compact('transactions','places','app','totalActiveTrans'));
        } catch (\Exception $e) {
            Alert::toast($e->getMessage(),'error');
            return redirect()->back();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DataRequest $request)
    {
        try {
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
                'activity' => 'Melakukan Tambah data, ID : ' . $data->id,
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
    public function destroy(string $id)
    {
        try {
            $del = Datas::findOrFail($id);

            UserActivity::create([
                'user_uuid' => Auth::user()->uuid,
                'activity' => 'Melakukan hapus data : ' . $del->customer_name,
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
