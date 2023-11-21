<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DatasExport implements FromView
{
    protected $data;

    public function __construct($data) {
        $this->data = $data;
    }
    public function view(): View
    {
        // dd($this->data);
        return view('pages.admin.all-data.export.exportAll',["data"=>$this->data]);
    }
}
