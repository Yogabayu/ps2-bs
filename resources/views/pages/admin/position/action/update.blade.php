@extends('layouts.admin.app')

@section('title', 'Edit Posisi')

@push('style')
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <a href="{{ route('position.index') }}">
                    <button class="btn btn-secondary mr-2"> <i class="fas fa-arrow-left mr-2"></i></button>
                </a>
                <h1>Tambah Posisi</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ url('/dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('position.index') }}">Position</a></div>
                    <div class="breadcrumb-item">Tambah Posisi</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <form action="{{ route('position.update', $data->id) }}" method="post">
                            @csrf
                            @method('put')
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Nama</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-person"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" placeholder="Nama Posisi"
                                                name="name" value="{{ $data->name }}" required>
                                        </div>
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
