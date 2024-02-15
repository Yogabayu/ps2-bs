        <!-- Modal -->
        <div class="modal fade" id="exampleModal{{ $dataId }}" tabindex="-1" role="dialog"
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

                        {{-- <iframe src="{{ asset('file/datas/' . $data->evidence_file) }}" frameborder="0" width="100%"
                            height="500"></iframe> --}}
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <a href="{{ asset('file/datas/' . $data->evidence_file) }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-primary form-control"> lihat video</a>
                                </div>
                                <div class="form-group">
                                    <label for="username">User</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        </div>
                                        <input type="text" id="username" class="form-control" value="{{ $data->user->name }}" autocomplete="{{ $data->user->name }}"
                                            readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="transactionCode">Transaksi</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-cash-register"></i>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control"
                                            value="{{ $data->transaction->code }} - {{ $data->transaction->name }}" id="transactionCode"
                                            readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="date">Tanggal</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar"></i>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" id="date"
                                            value="{{ \Carbon\Carbon::parse($data->date)->format('d-m-Y') }}" readonly>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="endDate">Batas Waktu</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-clock"></i>
                                            </div>
                                        </div>
                                        <input type="text" id="endDate" class="form-control"
                                            value="{{ $data->transaction->max_time }}" readonly>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="startDate">Waktu Mulai</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-clock"></i>
                                            </div>
                                        </div>
                                        <input type="text" id="startDate" class="form-control" value="{{ $data->start }}" readonly>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="finishDate">Waktu Selesai</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-clock"></i>
                                            </div>
                                        </div>
                                        <input type="text" id="finishDate" class="form-control" value="{{ $data->end }}" readonly>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="rek">No Rekening</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-wallet"></i>
                                            </div>
                                        </div>
                                        <input id="rek" type="text" class="form-control" value="{{ $data->no_rek }}" readonly>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="placeTransc">Tempat Transaksi</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-location-dot"></i>
                                            </div>
                                        </div>
                                        <input type="text" id="placeTransc" class="form-control"
                                            value="{{ $data->placeTransc->name }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="result">Hasil</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-square-poll-vertical"></i>
                                            </div>
                                        </div>
                                        <input type="text" id="result" class="form-control" readonly
                                            value="{{ $data->result == 1 ? 'onTime' : 'outTime' }}">
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
