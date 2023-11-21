<?php

namespace App\Http\Controllers\Spv;

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

class AllDataController extends Controller
{
    public function export(Request $request)
    {
        try {
            $query = DB::table('subordinates as s')
                ->join('users as u', 's.subordinate_uuid', '=', 'u.uuid')
                ->join('datas as d', 'u.uuid', '=', 'd.user_uuid')
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
                ->where('s.supervisor_id', Auth::user()->uuid)
                ->orderBy('d.id', 'desc');

            $data = $query->get();
            $filename = 'semua data ' . Carbon::now()->format('Y-m-d');

            UserActivity::create([
                'user_uuid' => Auth::user()->uuid,
                'activity' => 'Melakukan export ' . ($request->type == 1 ? 'excel' : 'pdf') . ': ' . $filename,
            ]);

            if ($request->type == 1) {
                return Excel::download(new DatasExport($data), $filename . '.xlsx');
            } else {
                $pdf = Pdf::loadView('pages.export.exportAll', ['data' => $data])->setPaper('legal', 'landscape');
                return $pdf->download($filename . '.pdf');
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
            $totalActiveTrans = DB::table('subordinates as s')
                ->join('users as u', 's.subordinate_uuid', '=', 'u.uuid')
                ->where('u.isActive', '=', 1)
                ->where('u.position_id', '!=', 1)
                ->where('s.supervisor_id', Auth::user()->uuid)
                ->count();
            $datas = DB::table('subordinates as s')
                ->join('users as u', 's.subordinate_uuid', '=', 'u.uuid')
                ->join('positions as p', 'p.id', '=', 'u.position_id')
                ->join('offices as o', 'o.id', '=', 'u.office_id')
                ->join('datas as d', 'd.user_uuid', '=', 'u.uuid')
                ->join('transactions as t', 't.id', '=', 'd.transc_id')
                ->join('place_transcs as pt', 'pt.id', '=', 'd.place_transc_id')
                ->where('s.supervisor_id', Auth::user()->uuid)
                ->select('d.*', 'u.name as username', 'p.name as namePosition', 'o.name as nameOffice', 'o.code as codeOffice', 't.code as codeTransaction', 't.name as nameTransaction', 't.max_time as maxTimeTrans', 'pt.code as ptcode', 'pt.name as ptname')
                ->orderBy('d.created_at', 'desc')
                ->distinct()
                ->get();

            return view('pages.spv.all-data.index', compact('app', 'totalActiveTrans', 'datas'));
        } catch (\Exception $e) {
            Alert::toast();
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
    public function destroy($id)
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
