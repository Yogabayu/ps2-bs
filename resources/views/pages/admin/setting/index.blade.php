@extends('layouts.admin.app')

@section('title', 'Setting')

@push('style')
    <link rel="stylesheet" href="{{ asset('stisla/library/summernote/dist/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('stisla/library/codemirror/lib/codemirror.css') }}">
    <link rel="stylesheet" href="{{ asset('stisla/library/codemirror/theme/duotone-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('stisla/library/selectric/public/selectric.css') }}">
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
                        <form action="{{ route('setting-app.update', $app->id) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Nama Aplikasi</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-person"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" placeholder="Nama Posisi"
                                                name="name_app" value="{{ $app->name_app }}" required>
                                        </div>
                                    </div>

                                    <img alt="image" src="{{ asset('file/setting/' . $app->logo) }}"
                                        class="mx-auto d-block img-fluid" style="max-width: 50%;">

                                    <div class="form-group">
                                        <label>Logo App</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            </div>
                                            <input type="file" class="form-control" name="logo"
                                                accept="image/jpeg, image/png">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Version</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-code-compare"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" name="version"
                                                value="{{ $app->version }}" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Deskripsi</label>
                                        <textarea class="summernote" name="desc">{{ $app->desc }}</textarea>
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
    <script src="{{ asset('stisla/library/summernote/dist/summernote-bs4.js') }}"></script>
    <script src="{{ asset('stisla/library/codemirror/lib/codemirror.js') }}"></script>
    <script src="{{ asset('stisla/library/codemirror/mode/javascript/javascript.js') }}"></script>
    <script src="{{ asset('stisla/library/selectric/public/jquery.selectric.min.js') }}"></script>
@endpush
