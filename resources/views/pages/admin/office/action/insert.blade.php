@extends('layouts.admin.app')

@section('title', 'Add Office')

@push('style')
@endpush
//TODO: controller masih kosong
@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Add Office</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ url('/dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('office.index') }}">Office</a></div>
                    <div class="breadcrumb-item">Add Office</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <form action="{{ route('user.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('post')
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Code</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-voicemail"></i>
                                                </div>
                                            </div>
                                            <input type="number" class="form-control" placeholder="code office"
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
                                            <input type="text" class="form-control" placeholder="Nama kantor"
                                                name="name" required>
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
