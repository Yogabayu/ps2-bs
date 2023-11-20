@extends('layouts.spv.app')

@section('title', 'Profile')

@push('style')
@endpush
@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Setting</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <form action="{{ route('s-profile.update', $profile->uuid) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="card">
                                <div class="card-body">

                                    <img alt="image" src="{{ asset('file/profile/' . $profile->photo) }}"
                                        class="mx-auto d-block img-fluid" style="max-width: 20%; max-height: 20%;">

                                    <div class="form-group">
                                        <label>NIK</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-hashtag"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" placeholder="NIK" name="nik"
                                                value="{{ $profile->nik }}" required>
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
                                            <input type="text" class="form-control" placeholder="nama user"
                                                name="name" value="{{ $profile->name }}" required>
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
                                            <input type="email" class="form-control" placeholder="nama email"
                                                name="email" value="{{ $profile->email }}" required>
                                        </div>
                                    </div>

                                    {{-- <div class="form-group">
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
                                                        @if ($profile->office_id == $office->id) selected @endif>
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
                                                    <i class="fas fa-campground"></i>
                                                </div>
                                            </div>
                                            <select class="form-control" name="position_id" id="position_id">
                                                <option selected>-</option>
                                                @foreach ($positions as $position)
                                                    <option value="{{ $position->id }}"
                                                        @if ($profile->position_id == $position->id) selected @endif>
                                                        {{ $position->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> --}}
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
                                    <button class="btn btn-primary mr-1" type="submit">Update</button>
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
