<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Semua data</title>
    <style>
        #table-1 {
            border-collapse: collapse;
            width: 100%;
        }

        #table-1 th,
        #table-1 td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 5px;
        }

        #table-1 th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <table class="table-striped table" id="table-1">
        <thead>
            <tr>
                <th class="text-center">
                    No
                </th>
                <th>Nama</th>
                <th>Posisi</th>
                <th>Cabang</th>
                <th>Bulan Transaksi</th>
                <th>Tanggal Transaksi</th>
                <th>Kode Transaksi</th>
                <th>Keterangan Transaksi</th>
                <th>Tempat Transaksi</th>
                <th>No Rekening</th>
                <th>Mulai</th>
                <th>Selesai</th>
                <th>Lama</th>
                <th>Target Timerline</th>
                <th>Hasil</th>
                <th>File</th>
                <th>Note dari SPV / Administrator</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
            @endphp
            @foreach ($data as $d)
                <tr>
                    <td class="text-center">
                        {{ $no++ }}
                    </td>
                    <td>
                        {{ $d->username }}
                    </td>
                    <td>
                        {{ $d->positionName }}
                    </td>
                    <td>
                        {{ $d->officeName }}
                    </td>
                    <td>
                        {{ $d->blnTransaksi }}
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($d->date)->format('d-m-Y') }}
                    </td>
                    <td>
                        {{ $d->transactionCode }}
                    </td>
                    <td>
                        {{ $d->transactionName }}
                    </td>
                    <td>
                        {{ $d->ptName }}
                    </td>
                    <td>
                        {{ $d->no_rek }}
                    </td>
                    <td>
                        {{ $d->start }}
                    </td>
                    <td>
                        {{ $d->end }}
                    </td>
                    <td>
                        {{ $d->lamaTransaksi }}
                    </td>
                    <td>
                        {{ $d->transactionMaxTime }}
                    </td>
                    <td>
                        {{ $d->timeline }}
                    </td>
                    <td>
                        <a href="{{ url('file/datas/' . $d->evidence_file) }}" target="_blank"> disini </a>
                    </td>
                    <td>
                        {{ $d->note ?? '' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
