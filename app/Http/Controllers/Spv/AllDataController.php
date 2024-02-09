<?php

namespace App\Http\Controllers\Spv;

use App\Exports\DatasExport;
use App\Exports\PartialExport;
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
            if (
                auth()->user()->position_id == 2 && (auth()->user()->office_id == 3 || auth()->user()->office_id == 4)
            ) {
                if ($request->typeTrans !== "null" && $request->offices !== "null") {
                    $date = $request->input('date');
                    $officeName = DB::table('offices')->select('name')->where('id', $request->offices)->first();
                    $data = DB::table('users as u')
                        ->join('datas as d', 'u.uuid', '=', 'd.user_uuid')
                        ->join('transactions as t', 't.id', '=', 'd.transc_id')
                        ->join('positions as p', 'p.id', '=', 'u.position_id')
                        ->join('offices as o', 'o.id', '=', 'u.office_id')
                        ->select(
                            'u.name as username',
                            'p.name as positionName',
                            'o.name as officeName',
                            DB::raw('COUNT(*) as totalTransactions'),
                            DB::raw('SUM(CASE WHEN d.result = 1 THEN 1 ELSE 0 END) as totalOnTime'),
                            DB::raw('SUM(CASE WHEN d.result = 0 THEN 1 ELSE 0 END) as totalOutTime')
                        )
                        ->where('t.id', $request->typeTrans)
                        ->where('o.id', $request->offices)
                        ->where('d.user_uuid', Auth::user()->uuid)
                        ->whereYear('d.created_at', '=', date('Y', strtotime($date)))
                        ->whereMonth('d.created_at', '=', date('m', strtotime($date)))
                        ->orderBy('username')
                        ->groupBy('username', 'positionName', 'officeName')
                        ->get();

                    if ($request->type == 1) {
                        $filename = 'Data ' . $date . ' - ' . $officeName->name . '.xlsx';
                        UserActivity::create([
                            'user_uuid' => Auth::user()->uuid,
                            'activity' => 'Melakukan export excel : ' . $filename,
                        ]);

                        return Excel::download(new PartialExport($data), $filename);
                    } else {
                        $filename = 'Data  ' . $date . ' - ' . $officeName->name . '.pdf';
                        UserActivity::create([
                            'user_uuid' => Auth::user()->uuid,
                            'activity' => 'Melakukan export pdf : ' . $filename,
                        ]);

                        $pdf = Pdf::loadView('pages.export.exportPartial', ['data' => $data])->setPaper('legal', 'landscape');
                        return $pdf->download($filename);
                    }
                } else {
                    if ($request->type == 1) {
                        $data = DB::table('users as u')
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
                            ->orderBy('d.id', 'desc')
                            ->where('d.user_uuid', Auth::user()->uuid)
                            ->get();

                        $filename = 'semua data ' . Carbon::now()->format('Y-m-d') . '.xlsx';

                        UserActivity::create([
                            'user_uuid' => Auth::user()->uuid,
                            'activity' => 'Melakukan export excel : ' . $filename,
                        ]);

                        return Excel::download(new DatasExport($data), $filename);
                    } else {
                        $data = DB::table('users as u')
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
                            ->orderBy('d.id', 'desc')
                            ->where('d.user_uuid', Auth::user()->uuid)
                            ->get();

                        $filename = 'semua data ' . Carbon::now()->format('Y-m-d') . '.pdf';
                        $pdf = Pdf::loadView('pages.export.exportAll', ['data' => $data])->setPaper('legal', 'landscape');

                        UserActivity::create([
                            'user_uuid' => Auth::user()->uuid,
                            'activity' => 'Melakukan export pdf : ' . $filename,
                        ]);
                        return $pdf->download($filename);
                    }
                }
            } else {
                if ($request->typeTrans !== "null" && $request->offices !== "null") {
                    $date = $request->input('date');
                    $officeName = DB::table('offices')->select('name')->where('id', $request->offices)->first();
                    $data = DB::table('subordinates as s')
                        ->join('users as u', 's.subordinate_uuid', '=', 'u.uuid')
                        ->join('datas as d', 'u.uuid', '=', 'd.user_uuid')
                        ->join('transactions as t', 't.id', '=', 'd.transc_id')
                        ->join('positions as p', 'p.id', '=', 'u.position_id')
                        ->join('offices as o', 'o.id', '=', 'u.office_id')
                        ->select(
                            'u.name as username',
                            'p.name as positionName',
                            'o.name as officeName',
                            DB::raw('COUNT(*) as totalTransactions'),
                            DB::raw('SUM(CASE WHEN d.result = 1 THEN 1 ELSE 0 END) as totalOnTime'),
                            DB::raw('SUM(CASE WHEN d.result = 0 THEN 1 ELSE 0 END) as totalOutTime')
                        )
                        ->where('t.id', $request->typeTrans)
                        ->where('o.id', $request->offices)
                        ->where('s.supervisor_id', Auth::user()->uuid)
                        ->whereYear('d.created_at', '=', date('Y', strtotime($date)))
                        ->whereMonth('d.created_at', '=', date('m', strtotime($date)))
                        ->orderBy('username')
                        ->groupBy('username', 'positionName', 'officeName')
                        ->get();

                    if ($request->type == 1) {
                        $filename = 'Data ' . $date . ' - ' . $officeName->name . '.xlsx';
                        UserActivity::create([
                            'user_uuid' => Auth::user()->uuid,
                            'activity' => 'Melakukan export excel : ' . $filename,
                        ]);

                        return Excel::download(new PartialExport($data), $filename);
                    } else {
                        $filename = 'Data  ' . $date . ' - ' . $officeName->name . '.pdf';
                        UserActivity::create([
                            'user_uuid' => Auth::user()->uuid,
                            'activity' => 'Melakukan export pdf : ' . $filename,
                        ]);

                        $pdf = Pdf::loadView('pages.export.exportPartial', ['data' => $data])->setPaper('legal', 'landscape');
                        return $pdf->download($filename);
                    }
                } else {
                    if ($request->type == 1) {
                        $data = DB::table('subordinates as s')
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
                            ->orderBy('d.id', 'desc')
                            ->where('s.supervisor_id', Auth::user()->uuid)
                            ->get();

                        $filename = 'semua data ' . Carbon::now()->format('Y-m-d') . '.xlsx';

                        UserActivity::create([
                            'user_uuid' => Auth::user()->uuid,
                            'activity' => 'Melakukan export excel : ' . $filename,
                        ]);

                        return Excel::download(new DatasExport($data), $filename);
                    } else {
                        $data = DB::table('subordinates as s')
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
                            ->orderBy('d.id', 'desc')
                            ->where('s.supervisor_id', Auth::user()->uuid)
                            ->get();

                        $filename = 'semua data ' . Carbon::now()->format('Y-m-d') . '.pdf';
                        $pdf = Pdf::loadView('pages.export.exportAll', ['data' => $data])->setPaper('legal', 'landscape');

                        UserActivity::create([
                            'user_uuid' => Auth::user()->uuid,
                            'activity' => 'Melakukan export pdf : ' . $filename,
                        ]);
                        return $pdf->download($filename);
                    }
                }
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
                ->where('u.position_id', '!=', 2)
                ->where('s.supervisor_id', Auth::user()->uuid)
                ->count();
            if (auth()->user()->position_id == 2 && (auth()->user()->office_id == 3 || auth()->user()->office_id == 4)) {
                $datas = DB::table('users as u')
                    ->join('positions as p', 'p.id', '=', 'u.position_id')
                    ->join('offices as o', 'o.id', '=', 'u.office_id')
                    ->join('datas as d', 'd.user_uuid', '=', 'u.uuid')
                    ->join('transactions as t', 't.id', '=', 'd.transc_id')
                    ->join('place_transcs as pt', 'pt.id', '=', 'd.place_transc_id')
                    ->where('d.user_uuid', Auth::user()->uuid)
                    ->select('d.*', 'u.name as username', 'p.name as namePosition', 'o.name as nameOffice', 'o.code as codeOffice', 't.code as codeTransaction', 't.name as nameTransaction', 't.max_time as maxTimeTrans', 'pt.code as ptcode', 'pt.name as ptname')
                    ->orderBy('d.created_at', 'desc')
                    ->distinct()
                    ->get();
            } else {
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
            }

            // dd($datas);
            $typeTrans = DB::table('transactions')->select('id', 'name', 'position_id', 'code')->get();
            if (auth()->user()->position_id == 1) {
                $offices = DB::table('offices')->select('id', 'code', 'name')->get();
            } else if (auth()->user()->position_id == 2) {
                $offices = DB::table('offices')
                    ->join('users', 'offices.id', '=', 'users.office_id')
                    ->select('offices.id', 'offices.code', 'offices.name')
                    ->where('users.office_id', auth()->user()->office_id)
                    ->distinct()
                    ->get();
            }
            return view('pages.spv.all-data.index', compact('app', 'totalActiveTrans', 'datas', "typeTrans", "offices"));
        } catch (\Exception $e) {
            dd($e->getMessage());
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
