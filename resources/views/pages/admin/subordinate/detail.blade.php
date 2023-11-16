@extends('layouts.admin.app')

@section('title', 'Tipe Transaksi')

@push('style')
    <!-- CSS Libraries -->
    {{-- <link rel="stylesheet"
        href="assets/modules/datatables/datatables.min.css">
    <link rel="stylesheet"
        href="assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet"
        href="assets/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css"> --}}

    <link rel="stylesheet" href="{{ asset('stisla/library/datatables/media/css/jquery.dataTables.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <a href="{{ route('subordinate.index') }}">
                    <button class="btn btn-secondary mr-2"> <i class="fas fa-arrow-left mr-2"></i></button>
                </a>
                <h1>Kantor: {{ $office->name ?? '-' }}</h1>
            </div>

            <div class="section-body">
                <h2 class="section-title">Supervisor Operational: {{ $spv_data->name ?? '-' }} </h2>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            {{-- <div class="card-header">
                                <h4>{{ $office->name }}</h4>
                            </div> --}}
                            <div class="card-body">
                                <div class="card-body">
                                    @if ($spv_data !== null)
                                        <a class="ml-2" data-toggle="modal" data-target="#addModal" data-backdrop="true"
                                            data-keyboard="false">
                                            <button class="btn btn-primary my-3">
                                                <i class="fas fa-file-add"></i> Tambah Data
                                            </button>
                                        </a>
                                    @endif
                                    <div class="table-responsive">
                                        <table class="table-striped table" id="table-1">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">
                                                        No
                                                    </th>
                                                    <th>Photo</th>
                                                    <th>Nama</th>
                                                    <th>Email</th>
                                                    <th>Cabang</th>
                                                    <th>Posisi</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $no = 1;
                                                @endphp

                                                @foreach ($subordinates as $data)
                                                    <tr>
                                                        <td>
                                                            {{ $no++ }}
                                                        </td>
                                                        <td>
                                                            <img alt="image"
                                                                src="{{ asset('file/profile/' . $data->photo) }}"
                                                                class="rounded-circle" width="35" data-toggle="tooltip"
                                                                title="{{ $data->name }}">
                                                        </td>
                                                        <td>
                                                            {{ $data->name }}
                                                        </td>
                                                        <td>
                                                            {{ $data->email }}
                                                        </td>
                                                        <td>
                                                            {{ $data->office_code }} - {{ $data->office_name }}
                                                        </td>
                                                        <td>
                                                            {{ $data->position_name }}
                                                        </td>
                                                        <td>
                                                            {{-- <a href="{{ route('user.show', $data->id) }}"
                                                                class="btn btn-info btn-sm" title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </a> --}}

                                                            <button class="btn btn-danger btn-sm" title="Delete"
                                                                onclick="confirmDelete('{{ route('subordinate.destroy', $data->id_sub) }}')">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Modal -->
        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" style="z-index: 9999">
            <div class="modal-dialog " role="document">
                <form action="{{ route('subordinate.store') }}" method="post">
                    @csrf
                    @method('post')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Masukkan data</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="office_id" value="{{ $office->id }}">
                            <input type="hidden" name="spv_uuid" value="{{ $spv_data->uuid ?? 0 }}">
                            <div class="form-group">
                                <label for="exportType">Pilih user:</label>
                                <select class="form-control" name="user_uuid" id="user_uuid">
                                    @foreach ($users as $user)
                                        <option value="{{ $user->uuid }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('vendor/sweetalert/sweetalert.all.js') }}"></script>
    <script src="{{ asset('stisla/library/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('stisla/library/jquery-ui-dist/jquery-ui.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script>
        "use strict";

        $("#table-1").dataTable({
            columnDefs: [{
                sortable: false,
                targets: []
            }],
        });
    </script>
    <script>
        function confirmDelete(deleteUrl) {
            Swal.fire({
                title: 'Apakah anda yakin menghapus data?',
                text: 'Data yang dihapus tidak dapat dipulihkan',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, lanjutkan !'
            }).then((result) => {
                if (result.isConfirmed) {
                    // If the user clicks "Yes," submit the form
                    var form = document.createElement('form');
                    form.action = deleteUrl;
                    form.method = 'POST';
                    form.style.display = 'none';

                    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    // Append CSRF token to the form
                    var csrfInput = document.createElement('input');
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;
                    form.appendChild(csrfInput);

                    // Append a method spoofing input for DELETE request
                    var methodInput = document.createElement('input');
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endpush
