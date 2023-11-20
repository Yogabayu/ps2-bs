        <!-- Modal -->
        <div class="modal fade" id="detailModal{{ $dataId }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 9999">
            <div class="modal-dialog " role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Detail Data {{ $dataId }}</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-12">
                                <form action="{{ route('s-listuser.update', $data->uuid) }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('put')
                                    <div class="card">
                                        <div class="card-body">
                                            <img alt="image" src="{{ asset('file/profile/' . $data->photo) }}"
                                                class="mx-auto d-block img-fluid" style="max-width: 50%;">
                                            <div class="form-group">
                                                <label>NIK</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-voicemail"></i>
                                                        </div>
                                                    </div>
                                                    <input type="number" class="form-control" placeholder="NIK"
                                                        name="nik" value="{{ $data->nik }}">
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
                                                        name="name" value="{{ $data->name }}">
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
                                                        name="email" value="{{ $data->email }}">
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
                                                                @if ($data->office_id == $office->id) selected @endif>
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
                                                                @if ($data->position_id == $position->id) selected @endif>
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
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                            <button class="btn btn-primary mr-1" type="submit">Update</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
