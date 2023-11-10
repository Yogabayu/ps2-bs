@extends('layouts.admin.app')

@section('title', 'Tambah User')

@push('style')
@endpush

@section('main')
    <div class="main-content">
        <section class="section">

            <div class="section-header">
                <a href="{{ route('user.index') }}">
                    <button class="btn btn-secondary mr-2"> <i class="fas fa-arrow-left mr-2"></i></button>
                </a>
                <h1>Add User</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ url('/dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('user.index') }}">Users</a></div>
                    <div class="breadcrumb-item">Add User</div>
                </div>
            </div>

            <div class="section-body">

                <div class="row">

                    <div class="col-12 col-md-12 col-lg-12">
                        <form action="{{ route('user.update', $user->uuid) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="card">
                                <div class="card-body">
                                    <img alt="image" src="{{ asset('file/profile/' . $user->photo) }}"
                                        class="mx-auto d-block img-fluid" style="max-width: 50%;">
                                    <div class="form-group">
                                        <label>NIK</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-voicemail"></i>
                                                </div>
                                            </div>
                                            <input type="number" class="form-control" placeholder="NIK" name="nik"
                                                value="{{ $user->nik }}">
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
                                            <input type="text" class="form-control" placeholder="Nama user"
                                                name="name" value="{{ $user->name }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>E-mail</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-envelope"></i>
                                                </div>
                                            </div>
                                            <input type="email" class="form-control" placeholder="E-mail user"
                                                name="email" value="{{ $user->email }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Cabang</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-building"></i>
                                                </div>
                                            </div>
                                            <select class="form-control" name="office_id" id="office_id">
                                                <option selected>-</option>
                                                @foreach ($offices as $office)
                                                    <option value="{{ $office->id }}"
                                                        @if ($user->office_id == $office->id) selected @endif>
                                                        {{ $office->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Posisi</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-building"></i>
                                                </div>
                                            </div>
                                            <select class="form-control" name="position_id" id="position_id">
                                                <option selected>-</option>
                                                @foreach ($positions as $position)
                                                    <option value="{{ $position->id }}"
                                                        @if ($user->position_id == $position->id) selected @endif>
                                                        {{ $position->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Password</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-lock"></i>
                                                </div>
                                            </div>
                                            <input type="password" class="form-control" placeholder="Password"
                                                name="password">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Photo</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            </div>
                                            <input type="file" class="form-control" name="photo"
                                                accept="image/jpeg, image/png">
                                        </div>
                                        <div class="text-danger">
                                            tipe: jpg, jpeg, png | max: 2 Mb
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <button class="btn btn-primary mr-1" type="submit">Submit</button>

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
