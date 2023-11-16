@extends('layouts.admin.app')

@section('title', 'Tipe Transaksi')

@push('style')
    <!-- CSS Libraries -->
    {{-- <link rel="stylesheet"
        href="assets/modules/datatables/datatables.min.css">
    <link rel="stylesheet"
        href="assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet"
        href="assets/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css"> --}}

    <link rel="stylesheet" href="{{ asset('stisla/library/datatables/media/css/jquery.dataTables.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Subordinate Menu</h1>
            </div>

            <div class="section-body">

                <h2 class="section-title">Pilih Kantor: </h2>
                <div class="row">
                    @foreach ($offices as $office)
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>{{ $office->name }}</h4>
                                </div>
                                <div class="card-body">
                                    <span>SPV: {{ $office->supervisor->name ?? '--' }}</span>
                                </div>
                                <div class="card-footer">
                                    <form action="{{ route('detail-subordinate') }}" method="get">
                                        @csrf
                                        @method('get')
                                        <input type="hidden" name="office_id" value="{{ $office->id }}">

                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-file-export"></i> Detail</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('vendor/sweetalert/sweetalert.all.js') }}"></script>
    <script src="{{ asset('stisla/library/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('stisla/library/jquery-ui-dist/jquery-ui.min.js') }}"></script>
@endpush
