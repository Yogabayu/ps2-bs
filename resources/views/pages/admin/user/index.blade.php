@extends('layouts.admin.app')

@section('title', 'User')

@push('style')
    <link rel="stylesheet" href="{{ asset('stisla/library/datatables/media/css/jquery.dataTables.min.css') }}">
@endpush
{{-- //URUNG tambah reset password  --}}
@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>User's Menu</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <a href="{{ route('user.create') }}">
                                    <button class="btn btn-primary my-3">
                                        <i class="fas fa-add"></i> Add user
                                    </button>
                                </a>
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
                                            @foreach ($datas as $data)
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
                                                        {{ $data->office->code }} - {{ $data->office->name }}
                                                    </td>
                                                    <td>
                                                        {{ $data->position->name }}
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('user.show', $data->id) }}"
                                                            class="btn btn-info btn-sm" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>

                                                        <button class="btn btn-danger btn-sm" title="Delete"
                                                            onclick="confirmDelete('{{ route('user.destroy', $data->id) }}')">
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
        </section>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('vendor/sweetalert/sweetalert.all.js') }}"></script>
    <script src="{{ asset('stisla/library/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('stisla/library/jquery-ui-dist/jquery-ui.min.js') }}"></script>

    <!-- Page Specific JS File -->
    {{-- <script src="{{ asset('stisla/js/page/modules-datatables.js') }}"></script> --}}
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
