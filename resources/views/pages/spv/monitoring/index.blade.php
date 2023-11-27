@extends('layouts.spv.app')

@section('title', 'Monitoring')

@push('style')
    <link rel="stylesheet" href="{{ asset('stisla/library/datatables/media/css/jquery.dataTables.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Monitoring</h1>
                <a onclick="window.location.reload();">
                    <i class="fas fa-rotate"></i>
                </a>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table-striped table" id="table-1">
                                        <thead>
                                            <tr>
                                                <th class="text-center">
                                                    No
                                                </th>
                                                <th>Photo</th>
                                                <th>Nama</th>
                                                <th>Kantor</th>
                                                <th>Posisi</th>
                                                <th>
                                                    Total Activitas <a href="#" data-container="body"
                                                        data-toggle="popover" data-placement="top"
                                                        data-content="Total aktivitas yang dilakukan hari ini">
                                                        <i class="fas fa-question"></i>
                                                    </a>
                                                </th>
                                                <th>Status <a href="#" data-container="body" data-toggle="popover"
                                                        data-placement="top" data-content="Status Transaksi user">
                                                        <i class="fas fa-question"></i>
                                                    </a></th>
                                                <th>Data Terakhir</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = 1;
                                            @endphp
                                            @foreach ($userActives as $data)
                                                <tr class="text-center">
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
                                                        {{ $data->office_name }}
                                                    </td>
                                                    <td>
                                                        {{ $data->position_name }}
                                                    </td>
                                                    <td>
                                                        {{ $data->totalActivity }}
                                                    </td>
                                                    <td>
                                                        @if ($data->isProcessing === 1)
                                                            Sedang melakukan input data
                                                        @else
                                                            Tidak melakukan input data
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <form action="{{ route('s-last-monitoring') }}" method="POST">
                                                            @csrf
                                                            @method('POST')

                                                            <input type="hidden" name="last_uuid"
                                                                value="{{ $data->uuid }}">

                                                            <button type="submit" class="btn btn-info btn-sm">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                        </form>
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
