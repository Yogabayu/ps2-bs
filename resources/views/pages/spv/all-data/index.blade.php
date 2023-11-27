@extends('layouts.spv.app')

@section('title', 'Data')

@push('style')
    <link rel="stylesheet" href="{{ asset('stisla/library/datatables/media/css/jquery.dataTables.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>All Datas</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    {{-- <a href="#">
                                        <button class="btn btn-primary my-3">
                                            <i class="fas fa-add"></i> Add Data
                                        </button>
                                    </a> --}}
                                    <a class="ml-2" data-toggle="modal" data-target="#exportModal" data-backdrop="true"
                                        data-keyboard="false">
                                        <button class="btn btn-primary my-3">
                                            <i class="fas fa-file-export"></i> Export Data
                                        </button>
                                    </a>
                                </div>
                                <div class="table-responsive">
                                    <table class="table-striped table" id="table-1">
                                        <thead>
                                            <tr>
                                                <th class="text-center">
                                                    No
                                                </th>
                                                <th>Nama</th>
                                                <th>Transaksi</th>
                                                <th>Tanggal</th>
                                                <th>Nominal</th>
                                                <th>Nama Nasabah</th>
                                                <th>Tempat Transaksi</th>
                                                <th>Hasil</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = 1;
                                                use Illuminate\Support\Str;
                                            @endphp
                                            @foreach ($datas as $data)
                                                <tr>
                                                    <td class="text-center">
                                                        {{ $no++ }}
                                                    </td>
                                                    <td>
                                                        {{ Str::limit($data->username, 10) }}
                                                    </td>
                                                    <td>
                                                        {{ $data->codeTransaction }} -
                                                        {{ Str::limit($data->nameTransaction, 5) }}
                                                    </td>
                                                    <td>
                                                        {{ \Carbon\Carbon::parse($data->date)->format('d-m-Y') }}
                                                    </td>

                                                    <td>
                                                        Rp {{ number_format($data->nominal, 0, ',', '.') }}
                                                    </td>
                                                    <td>
                                                        {{ $data->customer_name }}
                                                    </td>
                                                    <td>
                                                        {{ $data->ptcode }} &mdash;
                                                        {{ $data->ptname }}
                                                    </td>
                                                    <td>
                                                        @if ($data->result == 1)
                                                            <span style="color: green;">onTime</span>
                                                        @else
                                                            <span style="color: red;">outTime</span>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        <a class="btn btn-info btn-sm" title="Edit" data-toggle="modal"
                                                            data-target="#detailModal{{ $data->id }}"
                                                            data-backdrop="false">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <button class="btn btn-danger btn-sm" title="Delete"
                                                            onclick="confirmDelete('{{ route('s-datas.destroy', $data->id) }}')">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                @include('pages.spv.all-data.modal.all-data-model', [
                                                    'dataId' => $data->id,
                                                ])
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
        <!-- Modal -->
        <div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" style="z-index: 9999">
            <div class="modal-dialog " role="document">
                <form action="{{ route('s-data-export') }}" method="post">
                    @csrf
                    @method('post')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Export Data</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            {{-- //URUNG = controller blm dibuat --}}
                            <div class="form-group">
                                <label for="exportType">Select Export Type:</label>
                                <select class="form-control" name="type" id="type">
                                    <option value="1">Excel</option>
                                    <option value="2">PDF</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
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
