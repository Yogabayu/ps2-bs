@extends('layouts.admin.app')

@section('title', 'Tambah Tipe transaksi')

@push('style')
@endpush
@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <a href="{{ route('transc-type.index') }}">
                    <button class="btn btn-secondary mr-2"> <i class="fas fa-arrow-left mr-2"></i></button>
                </a>
                <h1>Tambah Tipe Transaksi</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ url('/dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('transc-type.index') }}">Tipe Transaksi</a></div>
                    <div class="breadcrumb-item">Tambah Tipe Transaksi</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <form action="{{ route('transc-type.store') }}" method="post">
                            @csrf
                            @method('post')
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Posisi</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-building"></i>
                                                </div>
                                            </div>
                                            <select class="form-control" name="position_id" id="position_id" required>
                                                <option selected>-</option>
                                                @foreach ($positions as $position)
                                                    <option value="{{ $position->id }}">
                                                        {{ $position->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Kode</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-voicemail"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" placeholder="code transaksi"
                                                name="code" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Nama</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-person"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" placeholder="Nama" name="name"
                                                required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Durasi Waktu</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-clock"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control"
                                                placeholder="Durasi Waktu (jam:menit:detik)" name="max_time" required
                                                pattern="^(?:[0-1]?[0-9]|[2][0-3]):([0-5][0-9])(:[0-5][0-9])?$">
                                        </div>
                                        <small>Format: jam:menit:detik (contoh: 00:03:00)</small>
                                    </div>

                                </div>
                                <div class="card-footer text-right">
                                    <button class="btn btn-primary mr-1" type="submit">Submit</button>
                                    <button class="btn btn-secondary" type="reset">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection

@push('scripts')
@endpush
