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
                <th>Jumlah Transaksi</th>
                <th>Total OnTime</th>
                <th>Total OutTime</th>
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
                        {{ $d->totalTransactions }}
                    </td>
                    <td>
                        {{ $d->totalOnTime }}
                    </td>
                    <td>
                        {{ $d->totalOutTime }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
