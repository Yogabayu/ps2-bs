@extends('layouts.spv.app')

@section('title', 'SPV')

@push('style')
    <link rel="stylesheet" href="{{ asset('stisla/library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('stisla/library/summernote/dist/summernote-bs4.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Dashboard</h1>
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
    <script>
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
