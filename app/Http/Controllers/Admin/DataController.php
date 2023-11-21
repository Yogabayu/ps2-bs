<?php

namespace App\Http\Controllers\Admin;

use App\Exports\DatasExport;
use App\Http\Controllers\Controller;
use App\Models\Datas;
use App\Models\Setting;
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
                $pdf = Pdf::loadView('pages.admin.all-data.export.exportAll', ['data' => $data])->setPaper('legal', 'landscape');
                
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
