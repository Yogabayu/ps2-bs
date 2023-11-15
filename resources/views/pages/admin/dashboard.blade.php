@extends('layouts.admin.app')

@section('title', 'Admin Dashboard')

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
                {{-- total admin --}}
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <a href="{{ route('user.index') }}">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="far fa-user"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Admins</h4>
                                </div>
                                <div class="card-body">
                                    {{ $totalAdmin }}
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                {{-- total spv --}}
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <a href="{{ route('user.index') }}">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="far fa-user"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total SPV</h4>
                                </div>
                                <div class="card-body">
                                    {{ $totalSpv }}
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                {{-- total user --}}
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <a href="{{ route('user.index') }}">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="far fa-user"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total User</h4>
                                </div>
                                <div class="card-body">
                                    {{ $totalUser }}
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                {{-- total transaksi aktif --}}
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="far fa-newspaper"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Active Users</h4>
                            </div>
                            <div class="card-body">
                                {{ $totalActiveTrans }}
                            </div>
                        </div>
                    </div>
                </div>
                {{-- total transaksi ontime --}}
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="far fa-file"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>On Time transaction</h4>
                            </div>
                            <div class="card-body">
                                {{ $totalOnTime }}
                            </div>
                        </div>
                    </div>
                </div>
                {{-- total transaksi outtime --}}
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="far fa-file"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Out of time transactions</h4>
                            </div>
                            <div class="card-body">
                                {{ $totalOutTime }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-circle"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Online Users</h4>
                            </div>
                            <div class="card-body">
                                47
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
            <div class="row">
                {{-- grafik statistik --}}
                <div class="col-lg-8 col-md-12 col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Statistics</h4>
                            <div class="card-header-action">
                                <div class="btn-group">
                                    <form method="GET" action="{{ route('indexAdmin') }}">
                                        @csrf
                                        <button type="submit" name="filter" value="week"
                                            class="{{ $filter === 'week' || $filter === null ? 'btn btn-primary' : 'btn' }}">Week</button>
                                        <button type="submit" name="filter" value="month"
                                            class="{{ $filter === 'month' ? 'btn btn-primary' : 'btn' }}">Month</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="myChart" height="182"></canvas>
                        </div>
                    </div>
                </div>
                {{-- recent activities --}}
                <div class="col-lg-4 col-md-12 col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Recent Activities</h4>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled list-unstyled-border">
                                @if (!$userActivities->isEmpty())
                                    @foreach ($userActivities as $ua)
                                        <li class="media">
                                            <img class="rounded-circle mr-3" width="50"
                                                src="{{ asset('file/profile/' . $ua->user->photo) }}" alt="avatar">
                                            <div class="media-body">
                                                <div class="text-primary float-right">
                                                    {{ Carbon\Carbon::parse($ua->created_at)->format('d-m-Y') }}
                                                </div>
                                                <div class="media-title">{{ $ua->user->name }}</div>
                                                <span class="text-small text-muted">{{ $ua->activity }}</span>
                                            </div>
                                        </li>
                                    @endforeach
                                @else
                                    <div style="display: flex; justify-content: center;">
                                        <span class="flex justify-content-center">&mdash;</span>
                                    </div>

                                @endif
                            </ul>
                            <div class="pt-1 pb-1 text-center">
                                <a href="{{ route('user-activity.index') }}" class="btn btn-primary btn-lg btn-round">
                                    View All
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('stisla/library/simpleweather/jquery.simpleWeather.min.js') }}"></script>
    <script src="{{ asset('stisla/library/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ asset('stisla/library/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('stisla/library/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('stisla/library/summernote/dist/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('stisla/library/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>

    <!-- Page Specific JS File -->
    {{-- <script src="{{ asset('stisla/js/page/index-0.js') }}"></script> --}}
    <script>
        "use strict";

        var statistics_chart = document.getElementById("myChart").getContext("2d");

        var myChart = new Chart(statistics_chart, {
            type: "line",
            data: {
                labels: {!! json_encode($dataGrafikChart->pluck('unit')->toArray()) !!},
                datasets: [{
                    label: 'input per' + '{!! $filter === 'week' ? 'minggu' : 'bulan' !!}: ',
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
                    display: false,
                },
                scales: {
                    yAxes: [{
                        gridLines: {
                            display: false,
                            drawBorder: false,
                        },
                        ticks: {
                            stepSize: 150,
                        },
                    }, ],
                    xAxes: [{
                        gridLines: {
                            color: "#fbfbfb",
                            lineWidth: 2,
                        },
                    }, ],
                },
            },
        });
    </script>
@endpush
