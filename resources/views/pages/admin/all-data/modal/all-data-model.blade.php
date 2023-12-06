        <!-- Modal -->
        <div class="modal fade" id="detailModal{{ $dataId }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 9999">
            <div class="modal-dialog " role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Detail Data</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <iframe src="{{ asset('file/datas/' . $data->evidence_file) }}" frameborder="0" width="100%"
                            height="500"></iframe>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>User</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" value="{{ $data->user->name }}"
                                            readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Transaksi</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-cash-register"></i>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control"
                                            value="{{ $data->transaction->code }} - {{ $data->transaction->name }}"
                                            readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Tanggal</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar"></i>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control"
                                            value="{{ \Carbon\Carbon::parse($data->date)->format('d-m-Y') }}" readonly>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Waktu Mulai</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-clock"></i>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" value="{{ $data->start }}" readonly>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Waktu Selesai</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-clock"></i>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" value="{{ $data->end }}" readonly>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Batas Waktu</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-clock"></i>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control"
                                            value="{{ $data->transaction->max_time }}" readonly>
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
                                                <i class="fas fa-money-bill"></i>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" value="{{ $data->no_rek }}"
                                            readonly>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Tempat Transaksi</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-location-dot"></i>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control"
                                            value="{{ $data->placeTransc->name }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
