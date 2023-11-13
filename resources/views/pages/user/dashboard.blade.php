@extends('layouts.user.app')

@section('title', 'User')

@push('style')
    <link rel="stylesheet" href="{{ asset('stisla/library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('stisla/library/summernote/dist/summernote-bs4.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Dashboard</h1>
            </div>
            <div class="section-body">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card">
                        <form id="waktuForm">
                            @csrf
                            <div class="card-header">
                                <h4>Input Profile</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-6 col-12">
                                        <label>Waktu Mulai: </label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button" onclick="setWaktu('mulai')">
                                                    Tapin
                                                </button>
                                            </div>
                                            <input type="text" class="form-control" id="waktuMulai"
                                                placeholder="Waktu Mulai" readonly />
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6 col-12">
                                        <label>Waktu Selesai</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button"
                                                    onclick="setWaktu('selesai')">
                                                    Tapout
                                                </button>
                                            </div>
                                            <input type="text" class="form-control" id="waktuSelesai"
                                                placeholder="Waktu Selesai" readonly />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Tanggal</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fas fa-building"></i>
                                                    </div>
                                                </div>
                                                <input type="date" name="date" id="date" class="form-control"
                                                    value="{{ now()->format('Y-m-d') }}" readonly>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Transaksi</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fas fa-building"></i>
                                                    </div>
                                                </div>
                                                <select class="form-control" name="transc_id" id="transc_id" required>
                                                    <option selected>-</option>
                                                    @foreach ($transactions as $transaction)
                                                        <option value="{{ $transaction->id }}">
                                                            {{ $transaction->code }} &mdash; {{ $transaction->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Tempat Transaksi</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fas fa-building"></i>
                                                    </div>
                                                </div>
                                                <select class="form-control" name="place_transc_id" id="place_transc_id"
                                                    required>
                                                    <option selected>-</option>
                                                    @foreach ($places as $place)
                                                        <option value="{{ $place->id }}">
                                                            {{ $place->code }} &mdash; {{ $place->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Nominal</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fas fa-building"></i>
                                                    </div>
                                                </div>
                                                <input type="number" name="nominal" id="nominal" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Nama Customer</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fas fa-building"></i>
                                                    </div>
                                                </div>
                                                <input type="text" name="customer_name" id="customer_name"
                                                    class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="button" class="btn btn-primary" onclick="hitungDurasi()">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('vendor/sweetalert/sweetalert.all.js') }}"></script>
    <!-- JS Libraies -->
    <script src="{{ asset('stisla/library/simpleweather/jquery.simpleWeather.min.js') }}"></script>
    <script src="{{ asset('stisla/library/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ asset('stisla/library/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('stisla/library/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('stisla/library/summernote/dist/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('stisla/library/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('stisla/js/page/index-0.js') }}"></script>

    <script>
        var waktuMulai = null;
        var waktuSelesai = null;
        let mediaRecorder;
        let recordedChunks = [];

        function setWaktu(jenis) {
            var waktu = new Date();
            var jam = waktu.getHours().toString().padStart(2, "0");
            var menit = waktu.getMinutes().toString().padStart(2, "0");
            var detik = waktu.getSeconds().toString().padStart(2, "0");
            var waktuString = jam + ":" + menit + ":" + detik;

            if (jenis === "mulai") {
                waktuMulai = waktuString;
                document.getElementById("waktuMulai").value = waktuMulai;
                startRecording();
            } else if (jenis === "selesai") {
                waktuSelesai = waktuString;
                document.getElementById("waktuSelesai").value = waktuSelesai;
                stopRecording();
            }
        }

        function startRecording() {
            navigator.mediaDevices
                .getDisplayMedia({
                    video: true
                })
                .then(function(stream) {
                    mediaRecorder = new MediaRecorder(stream);

                    mediaRecorder.ondataavailable = function(event) {
                        if (event.data.size > 0) {
                            recordedChunks.push(event.data);
                        }
                    };

                    mediaRecorder.onstop = function() {
                        var blob = new Blob(recordedChunks, {
                            type: "video/webm"
                        });
                        var videoUrl = URL.createObjectURL(blob);
                        document.getElementById("video").src = videoUrl;

                        recordedChunks = [];
                    };

                    mediaRecorder.start();
                })
                .catch(function(error) {
                    console.error("Error accessing screen: ", error);
                });
        }

        function stopRecording() {
            if (mediaRecorder && mediaRecorder.state === "recording") {
                mediaRecorder.stop();
            }
        }

        function hitungDurasi() {
            if (!waktuMulai || !waktuSelesai) {
                Swal.fire({
                    title: "Error!",
                    text: "Waktu mulai / selesai belum diatur.",
                    icon: "error"
                });
                return;
            }

            var durasiMulai = new Date("2000-01-01 " + waktuMulai);
            var durasiSelesai = new Date("2000-01-01 " + waktuSelesai);
            var durasiMilidetik = durasiSelesai - durasiMulai;
            var durasiDetik = Math.floor(durasiMilidetik / 1000);
            var durasiMenit = Math.floor(durasiDetik / 60);

            var sisaDetik = durasiDetik % 60;

            //final
            var user_uuid = '{{ Auth::user()->uuid }}';
            var transc_id = document.getElementById("transc_id").value;
            var place_transc_id = document.getElementById("place_transc_id").value;
            var date = document.getElementById("date").value;
            var start = waktuMulai;
            var end = waktuSelesai;
            var nominal = document.getElementById("nominal").value;
            var customer_name = document.getElementById("customer_name").value;
            var isActive = 0;
            // Mendapatkan hasil rekaman video sebagai Blob
            if (mediaRecorder && mediaRecorder.state === "recording") {
                mediaRecorder.stop();
            }

            // Membuat objek Blob dari recordedChunks
            var blob = new Blob(recordedChunks, {
                type: "video/webm"
            });

            var formData = new FormData();
            formData.append("user_uuid", user_uuid);
            formData.append("transc_id", transc_id);
            formData.append("place_transc_id", place_transc_id);
            formData.append("date", date);
            formData.append("start", start);
            formData.append("end", end);
            formData.append("nominal", nominal);
            formData.append("customer_name", customer_name);
            formData.append("isActive", isActive);
            formData.append("_token", "{{ csrf_token() }}");
            formData.append("evidence_file", blob, "rekaman.webm");

            fetch("{{ route('u-data.store') }}", {
                    method: "POST",
                    body: formData,
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log(data.message);
                    // Lakukan sesuatu dengan data yang diterima dari server
                })
                .catch(error => {
                    console.error("Fetch error:", error);
                    // Tangani kesalahan fetch jika ada
                });


        }
    </script>
@endpush