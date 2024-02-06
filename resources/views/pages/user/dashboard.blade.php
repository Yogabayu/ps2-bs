@extends('layouts.user.app')

@section('title', 'User')

@push('style')
    <link rel="stylesheet" href="{{ asset('stisla/library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('stisla/library/summernote/dist/summernote-bs4.min.css') }}">
    <style>
        .loading-indicator {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            z-index: 9999;
            /* Set a high z-index to ensure it appears on top */
            justify-content: center;
            align-items: center;
        }

        .spinner {
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-left-color: #333;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin-bottom: 10px;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Dashboard</h1>
            </div>
            <div class="section-body">
                <div id="loadingIndicator" class="loading-indicator">
                    <div class="spinner"></div>
                    <p>Loading...</p>
                </div>
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card">
                        <form id="waktuForm">
                            @csrf
                            <div class="card-header">
                                <h4>Input Data</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-6 col-12" style="display: none">
                                        <video id="video" controls></video>
                                    </div>
                                    <div class="form-group col-md-6 col-12">
                                        <label>Waktu Mulai: </label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button" onclick="setWaktu('mulai')">
                                                    Tapin
                                                </button>
                                            </div>
                                            <input type="text" class="form-control" id="waktuMulai"
                                                placeholder="Waktu Mulai" readonly />
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6 col-12">
                                        <label>Waktu Selesai</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button"
                                                    onclick="setWaktu('selesai')">
                                                    Tapout
                                                </button>
                                            </div>
                                            <input type="text" class="form-control" id="waktuSelesai"
                                                placeholder="Waktu Selesai" readonly />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Tanggal</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fas fa-building"></i>
                                                    </div>
                                                </div>
                                                <input type="date" name="date" id="date" class="form-control"
                                                    value="{{ now()->format('Y-m-d') }}" readonly>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Transaksi</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fas fa-building"></i>
                                                    </div>
                                                </div>
                                                <select class="form-control" name="transc_id" id="transc_id" required>
                                                    <option selected>-</option>
                                                    @foreach ($transactions as $transaction)
                                                        <option value="{{ $transaction->id }}">
                                                            {{ $transaction->code }} &mdash; {{ $transaction->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Tempat Transaksi</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fas fa-building"></i>
                                                    </div>
                                                </div>
                                                <select class="form-control" name="place_transc_id" id="place_transc_id"
                                                    required>
                                                    <option selected>-</option>
                                                    @foreach ($places as $place)
                                                        <option value="{{ $place->id }}">
                                                            {{ $place->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6 col-12">
                                        <div class="form-group">
                                            <label>No Rekening</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fas fa-wallet"></i>
                                                    </div>
                                                </div>
                                                <input type="text" name="no_rek" id="no_rek" class="form-control"
                                                    placeholder="0" pattern="[0-9]*">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="button" class="btn btn-primary" onclick="hitungDurasi()">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <section class="section">
            <div class="section-header">
                <h1>Ringkasan</h1>
            </div>
            <div class="row">
                {{-- total data --}}
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <a href="#">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="far fa-clock"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Data</h4>
                                </div>
                                <div class="card-body">
                                    {{ $totalData }}
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                {{-- total bulan ini --}}
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <a href="#">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="far fa-clock"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Bulan Ini</h4>
                                </div>
                                <div class="card-body">
                                    {{ $totalThisMonth }}
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                {{-- total bulan onTime --}}
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <a href="#">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-success">
                                <i class="far fa-clock"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total OnTime</h4>
                                </div>
                                <div class="card-body">
                                    {{ $totalResult1 }}
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                {{-- total bulan outTime --}}
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <a href="#">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-danger">
                                <i class="far fa-clock"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total OutTime</h4>
                                </div>
                                <div class="card-body">
                                    {{ $totalResult0 }}
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Grafik Input Data Perbulan</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="grafik1"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Grafik Total Data berdasarkan hasil akhir</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="pieChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('vendor/sweetalert/sweetalert.all.js') }}"></script>
    <!-- JS Libraies -->
    <script src="{{ asset('stisla/library/simpleweather/jquery.simpleWeather.min.js') }}"></script>
    <script src="{{ asset('stisla/library/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ asset('stisla/library/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('stisla/library/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('stisla/library/summernote/dist/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('stisla/library/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>
    <script src="{{ asset('stisla/library/chart.js/dist/Chart.min.js') }}"></script>

    <!-- Page Specific JS File -->
    {{-- <script>
        /* Dengan Rupiah */
        var dengan_rupiah = document.getElementById('nominal');
        dengan_rupiah.addEventListener('keyup', function(e) {
            dengan_rupiah.value = formatRupiah(this.value, 'Rp. ');
        });

        /* Fungsi */
        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }
    </script> --}}

    {{-- main script  --}}
    <script>
        function showLoadingIndicator() {
            document.getElementById("loadingIndicator").style.display = "flex";
        }

        function hideLoadingIndicator() {
            document.getElementById("loadingIndicator").style.display = "none";
        }
    </script>
    <script>
        var waktuMulai = null;
        var waktuSelesai = null;
        let mediaRecorder;
        let recordedChunks = [];

        function setWaktu(jenis) {
            var waktu = new Date();
            var jam = waktu.getHours().toString().padStart(2, "0");
            var menit = waktu.getMinutes().toString().padStart(2, "0");
            var detik = waktu.getSeconds().toString().padStart(2, "0");
            var waktuString = jam + ":" + menit + ":" + detik;

            if (jenis === "mulai") {
                waktuMulai = waktuString;
                document.getElementById("waktuMulai").value = waktuMulai;
                startRecording();
            } else if (jenis === "selesai") {
                waktuSelesai = waktuString;
                document.getElementById("waktuSelesai").value = waktuSelesai;
                stopRecording();
            }
        }

        function startRecording() {
            navigator.mediaDevices
                .getDisplayMedia({
                    video: true
                })
                .then(function(stream) {
                    mediaRecorder = new MediaRecorder(stream, {
                        audioBitsPerSecond: 128000,
                        videoBitsPerSecond: 2500000,
                        mimeType: "video/webm",
                    });

                    mediaRecorder.ondataavailable = function(event) {
                        if (event.data.size > 0) {
                            recordedChunks.push(event.data);
                        }
                    };

                    mediaRecorder.onstop = function() {
                        mediaRecorder.stop();
                    };

                    mediaRecorder.start();
                })
                .catch(function(error) {
                    console.error("Error accessing screen: ", error);
                });

            //update status
            var formUpdateData = new FormData();
            formUpdateData.append("isProcessing", 1);
            formUpdateData.append("_token", "{{ csrf_token() }}");

            fetch("{{ route('u-isprocessing') }}", {
                    method: "POST",
                    body: formUpdateData,
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        return;
                    } else {
                        console.log(data.message);
                    }
                })
                .catch(error => {
                    console.error("Fetch error:", error.message);
                });
        }

        function stopRecording() {
            if (mediaRecorder && mediaRecorder.state === "recording") {
                mediaRecorder.stop();
            }

            //update status
            var formUpdateData = new FormData();
            formUpdateData.append("isProcessing", 0);
            formUpdateData.append("_token", "{{ csrf_token() }}");

            fetch("{{ route('u-isprocessing') }}", {
                    method: "POST",
                    body: formUpdateData,
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        return;
                    } else {
                        console.log(data.message);
                    }
                })
                .catch(error => {
                    console.error("Fetch error:", error.message);
                });
        }

        function hitungDurasi() {
            if (!waktuMulai || !waktuSelesai) {
                Swal.fire({
                    title: "Error!",
                    text: "Waktu mulai / selesai belum diatur.",
                    icon: "error"
                });
                return;
            }

            var durasiMulai = new Date("2000-01-01 " + waktuMulai);
            var durasiSelesai = new Date("2000-01-01 " + waktuSelesai);
            var durasiMilidetik = durasiSelesai - durasiMulai;
            var durasiDetik = Math.floor(durasiMilidetik / 1000);
            var durasiMenit = Math.floor(durasiDetik / 60);

            var sisaDetik = durasiDetik % 60;

            //final
            var user_uuid = '{{ Auth::user()->uuid }}';
            var transc_id = document.getElementById("transc_id").value;
            var place_transc_id = document.getElementById("place_transc_id").value;
            var date = document.getElementById("date").value;
            var start = waktuMulai;
            var end = waktuSelesai;
            var no_rek = document.getElementById("no_rek").value;
            var isActive = 0;
            // Mendapatkan hasil rekaman video sebagai Blob
            if (mediaRecorder && mediaRecorder.state === "recording") {
                mediaRecorder.stop();
            }

            // Membuat objek Blob dari recordedChunks
            var blob = new Blob(recordedChunks, {
                type: "video/webm"
            });

            var formData = new FormData();
            formData.append("user_uuid", user_uuid);
            formData.append("transc_id", transc_id);
            formData.append("place_transc_id", place_transc_id);
            formData.append("date", date);
            formData.append("start", start);
            formData.append("end", end);
            formData.append("no_rek", no_rek);
            formData.append("isActive", isActive);
            formData.append("_token", "{{ csrf_token() }}");
            formData.append("evidence_file", blob, "rekaman.webm");
            showLoadingIndicator();

            fetch("{{ route('u-data.store') }}", {
                    method: "POST",
                    body: formData,
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    // console.log(data.message);

                    if (data.success) {
                        Swal.fire({
                            title: "Success!",
                            text: "Data berhasil disimpan.",
                            icon: "success"
                        });

                        // Reset formulir
                        document.getElementById("waktuForm").reset();
                        hideLoadingIndicator();
                        return;
                    } else {
                        Swal.fire({
                            title: "Error!",
                            text: data.message,
                            icon: "error"
                        });
                        hideLoadingIndicator();
                    }
                })
                .catch(error => {
                    if (data.success == false) {
                        Swal.fire({
                            title: "Error!",
                            text: error.message,
                            icon: "error"
                        });
                    }
                    hideLoadingIndicator();
                });

            recordedChunks = [];
            hideLoadingIndicator();
        }
    </script>

    {{-- chartJS --}}
    <script>
        "use strict";

        var ctx = document.getElementById("grafik1").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($dataGrafikChart->pluck('month')->toArray()) !!},
                datasets: [{
                    label: 'input perbulan: ',
                    data: {!! json_encode($dataGrafikChart->pluck('total')->toArray()) !!},
                    borderWidth: 2,
                    backgroundColor: '#6777ef',
                    borderColor: '#6777ef',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#ffffff',
                    pointRadius: 4
                }]
            },
            options: {
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        gridLines: {
                            drawBorder: false,
                            color: '#f2f2f2',
                        },
                        ticks: {
                            beginAtZero: true,
                            stepSize: 150
                        }
                    }],
                    xAxes: [{
                        ticks: {
                            display: false
                        },
                        gridLines: {
                            display: false
                        }
                    }]
                },
            }
        });

        var ctx = document.getElementById("pieChart").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                datasets: [{
                    data: [
                        {!! $dataGrafikChartPie->pluck('ontime')->first() ?? 0 !!},
                        {!! $dataGrafikChartPie->pluck('outtime')->first() ?? 0 !!},
                    ],
                    backgroundColor: [
                        '#63ed7a',
                        '#fc544b',
                    ],
                    label: 'Dataset 1'
                }],
                labels: [
                    'onTime',
                    'outTime',
                ],
            },
            options: {
                responsive: true,
                legend: {
                    position: 'bottom',
                },
            }
        });
    </script>
@endpush
