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
                <th>Target Timerline</th>
                <th>Mulai</th>
                <th>Selesai</th>
                <th>Lama</th>
                <th>Nominal</th>
                <th>Nama Nasabah</th>
                <th>Tempat Transaksi</th>
                <th>Timeline</th>
                <th>File</th>
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
                        {{ $d->date }}
                    </td>
                    <td>
                        {{ $d->transactionCode }}
                    </td>
                    <td>
                        {{ $d->transactionName }}
                    </td>
                    <td>
                        {{ $d->transactionMaxTime }}
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
                        {{ $d->nominal }}
                    </td>
                    <td>
                        {{ $d->customer_name }}
                    </td>
                    <td>
                        {{ $d->ptName }}
                    </td>
                    <td>
                        {{ $d->timeline }}
                    </td>
                    <td>
                        <a href="{{ url('file/datas/' . $d->evidence_file) }}" target="_blank"> disini </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
