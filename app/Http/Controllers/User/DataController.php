<?php

namespace App\Http\Controllers\User;

use App\Exports\DatasExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserRequest;
use App\Models\Datas;
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
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class DataController extends Controller
{
    public function export(Request $request)
    {
        try {
            $cekSubordinates = DB::table('subordinates')->where('subordinate_uuid', Auth::user()->uuid)->count();
            if ($cekSubordinates > 0) {
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
                        'pt.name as ptName',
                        DB::raw('MONTH(d.date) as blnTransaksi'),
                        DB::raw('SEC_TO_TIME(TIMESTAMPDIFF(SECOND, d.start, d.end)) as lamaTransaksi'),
                        DB::raw('CASE WHEN d.result = 1 THEN "Sesuai" ELSE "Tidak Sesuai" END as timeline'),
                    )
                    ->where('d.user_uuid', Auth::user()->uuid)
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
            } else {
                Alert::error('User belum mempunyai SPV, Silahkan hubungi administrator', 'error');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Alert::error($e->getMessage(), 'error');
            return redirect()->back();
        }
    }

    public function process(Request $request)
    {
        try {
            User::where('uuid', Auth::user()->uuid)->update(['isProcessing' => $request->isProcessing]);

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
    public function store(UserRequest $request)
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
            $data->no_rek           = $request->no_rek;
            $data->isActive         = 0;

            //upload file
            $imageEXT           = $request->file('evidence_file')->getClientOriginalName();
            $filename           = pathinfo($imageEXT, PATHINFO_FILENAME);
            $EXT                = $request->file('evidence_file')->getClientOriginalExtension();
            $fileimage          = $filename . '_' . time() . '.' . $EXT;
            $path               = $request->file('evidence_file')->move(public_path('file/datas'), $fileimage);
            $data->evidence_file = $fileimage;

            //calculate result
            if ($timeDifferenceInSeconds > $maxTimeInSeconds) {
                $data->result = 0;
                $users = DB::table('users as u')
                    ->join('offices as o', 'u.office_id', '=', 'o.id')
                    ->join('subordinates as s', 'o.supervisor_uuid', '=', 's.supervisor_id')
                    ->where('s.subordinate_uuid', $request->user_uuid)
                    ->where(function ($query) {
                        $query->where('u.position_id', 2)
                            ->orWhere('u.position_id', 1);
                    })
                    ->get();
                $detailOutUser = User::where('uuid', $request->user_uuid)->first();
                $detailRek = $request->no_rek;
                $detailDate = now();

                $this->sendToSPV($users, $detailOutUser, $detailRek, $detailDate);
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

    private function sendToSPV($spvs, $detailOutUser, $detailRek, $detailDate)
    {
        foreach ($spvs as $spv) {
            Mail::send('pages.email.notif', ['spv' => $spv, 'detailOutUser' => $detailOutUser, 'detailRek' => $detailRek, 'detailDate' => $detailDate], function ($message) use ($spv) {
                $message->from('cepatcatat@bankarthaya.com', 'Administrator');
                $message->to($spv->email, 'Admin');
                $message->subject('Notifikasi Email');
            });
        }
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
