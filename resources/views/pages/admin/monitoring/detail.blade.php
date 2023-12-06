@extends('layouts.admin.app')

@section('title', 'Setting')

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
                        <form>
                            <div class="card">
                                <div class="card-body">

                                    <iframe src="{{ asset('file/datas/' . $data->evidence_file) }}" frameborder="0"
                                        width="100%" height="500"></iframe>

                                    <div class="form-group">
                                        <label>Nama Akun</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-hashtag"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" value="{{ $data->username }}"
                                                required readonly>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Transaksi</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-person"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control"
                                                value="{{ $data->transcode }} - {{ $data->transname }}" required readonly>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Tempat Transaksi</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-person"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" value="{{ $data->placename }}"
                                                required readonly>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Tanggal Transaksi</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-person"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" value="{{ $data->date }}" required
                                                readonly>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Jam Mulai Transaksi</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-person"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" value="{{ $data->start }}" required
                                                readonly>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Jam Selesai Transaksi</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-person"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" value="{{ $data->end }}" required
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Target Time</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-square-poll-vertical"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" readonly
                                                value="{{ $data->transMaxTime }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Lama Transaksi</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-square-poll-vertical"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" readonly
                                                value="{{ $data->lamaTransaksi }}">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Hasil</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-square-poll-vertical"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" readonly
                                                value="{{ $data->result == 1 ? 'onTime' : 'outTime' }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Nomor Rekening</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-person"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" value="{{ $data->no_rek }}"
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="card-footer text-right">
                            <a href="{{ route('monitoring.index') }}"><button
                                    class="btn btn-primary mr-1">Kembali</button></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection

@push('scripts')
@endpush
