@extends('layouts.master')

@section('content')

    <head>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="{{ asset('js/fuzzy.js') }}"></script>
    </head>

    <body>
        <div class="main-panel">
            <div class="content">
                <div class="page-inner">
                    <div class="page-header">
                        <ul class="breadcrumbs">
                            <li class="nav-home">
                                <a href="/admin/datakaryawann">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="separator">
                                <i class="flaticon-right-arrow"></i>
                            </li>
                            <li class="nav-item">
                                <a href="/admin/datakaryawann">Detail Karyawan</a>
                            </li>
                        </ul>
                    </div>

                    <div class="col md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h4 class="card-title">Nama Karyawan : {{ $karyawan->nama_karyawan }}</h4>
                                    {{-- <button class="btn btn-primary btn-round ml-auto" data-toggle="modal"
                                    data-target="#addRowModal">
                                    <i class="fa fa-plus"></i>
                                    Tambah
                                </button> --}}
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="container">
                                    <div class="main-body">
                                        <div class="row gutters-sm">
                                            <div class="col-md-4 mb-3">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="d-flex flex-column align-items-center text-center">
                                                            <img src="https://bootdey.com/img/Content/avatar/avatar7.png"
                                                                alt="Admin" class="rounded-circle" width="150">
                                                            <div class="mt-3">
                                                                <h4>{{ $karyawan->nama_karyawan }}</h4>
                                                                <p class="text-secondary mb-1">{{ $karyawan->jabatan }}</p>
                                                                <p class="text-muted font-size-sm">{{ $karyawan->bidang }}
                                                                </p>
                                                                {{-- <button class="btn btn-primary">Edit</button>
                                                            <button class="btn btn-outline-primary">Detail</button> --}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="card mb-3">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-sm-3">
                                                                <h6 class="mb-0">Full Name</h6>
                                                            </div>
                                                            <div class="col-sm-9 text-secondary">
                                                                {{ $karyawan->nama_karyawan }}
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="row">
                                                            <div class="col-sm-3">
                                                                <h6 class="mb-0">Email</h6>
                                                            </div>
                                                            <div class="col-sm-9 text-secondary">
                                                                {{ $karyawan->nama_karyawan }}@gmail.com
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="row">
                                                            <div class="col-sm-3">
                                                                <h6 class="mb-0">Phone</h6>
                                                            </div>
                                                            <div class="col-sm-9 text-secondary">
                                                                +62814566732
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-sm-3">
                                                                <h6 class="mb-0">Alamat</h6>
                                                            </div>
                                                            <div class="col-sm-9 text-secondary">
                                                                Cirebon
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        {{-- @if ($karyawan_nilai->isNotEmpty() && $karyawan_nilai->count() === 12)
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <a class="btn btn-info" target="__blank"
                                                                        href="https://www.bootdey.com/snippets/view/profile-edit-data-and-skills">Hitung
                                                                        Rekomendasi</a>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="row">
                                                                <div class="col-sm-3">
                                                                    <h6 class="mb-0">Status</h6>
                                                                </div>
                                                                <div class="col-sm-9 text-secondary">
                                                                    {{ $karyawan_nilai->first()?->status ?? 'belum dikartap' }}
                                                                </div>
                                                            </div>
                                                            <br> --}}
                                                        @php
                                                            $tahunaktif = App\Models\TahunActive::find(1);

                                                            $tahun_nilai = \Carbon\Carbon::parse(
                                                                $tahunaktif->tahun_active,
                                                            )->year;
                                                            $formattedValue = number_format($tahun_nilai, 2);

                                                        @endphp
                                                        {{-- @if (auth()->user()->hasRole('penilai') && $karyawan_nilai->count() === 12)
                                                            <a href="{{ url('/admin/fuzzy/' . $id . '/' . $tahun_nilai) }}"
                                                                class="btn btn-primary">
                                                                Hitung Logic Fuzzy</a>
                                                        @endif --}}
                                                        @if (auth()->user()->hasRole('penilai') && $karyawan_nilai->count() == 12)
                                                            @php
                                                                // Mengecek apakah ada data bulan yang masih null
                                                                $bulanNull = $karyawan_nilai->contains(function (
                                                                    $nilai,
                                                                ) {
                                                                    return is_null($nilai->n1);
                                                                });
                                                            @endphp

                                                            @if ($bulanNull)
                                                                <div class="btn"
                                                                    style="background-color: rgb(202, 202, 202);   pointer-events: auto! important;
                                                          cursor: not-allowed! important;">
                                                                    Hitung Logic Fuzzy
                                                                    {{-- <a
                                                                        href="#">
                                                                    </a> --}}
                                                                </div>
                                                            @else
                                                                <a
                                                                    href="{{ url('/admin/fuzzy/' . $id . '/' . $tahun_nilai) }}">
                                                                    <button class="btn btn-primary">
                                                                        Hitung Logic Fuzzy
                                                                    </button>
                                                                </a>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- Table --}}
                                        {{-- <div class="table-responsive">
                                        <table id="add-row" class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th style="width: 6%">No</th>
                                                    <th>Periode</th>
                                                    @foreach ($v as $d)
                                                    <th>Bekerja Sama</th>
                                                    <th>Sesuai Tugas</th>
                                                    <th>Kepedulian</th>
                                                    <th>K3</th>
                                                    <th>Patuh Dan Taat</th>
                                                    <th>Tepat Waktu</th>
                                                    <th>Kesiapan</th>
                                                    <th>Apel Shift</th>
                                                    <th>Kelengkapan Atribut</th>
                                                    @endforeach
                                                    @if (auth()->user()->hasRole('penilai'))
                                                        <th>Aksi</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $no = 1;
                                                @endphp
                                                @foreach ($karyawan_nilai as $data)
                                                    <tr>
                                                        <td>{{ $no++ }}</td>
                                                        <td>{{ $data->periode->bulan }}</td>
                                                        <td>{{ $data->n1 }}</td>
                                                        <td>{{ $data->n2 }}</td>
                                                        <td>{{ $data->n3 }}</td>
                                                        <td>{{ $data->n4 }}</td>
                                                        <td>{{ $data->n5 }}</td>
                                                        <td>{{ $data->n6 }}</td>
                                                        <td>{{ $data->n7 }}</td>
                                                        <td>{{ $data->n8 }}</td>
                                                        <td>{{ $data->n9 }}</td>
                                                        @if (auth()->user()->hasRole('penilai'))
                                                            <td>
                                                                <button class="btn btn-link btn-success edit-button"
                                                                    id="updateButton" data-toggle="modal"
                                                                    data-target="#addRowModal" data-id="{{ $data->id }}">
                                                                    Input Nilai
                                                                </button>
                                                            </td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div> --}}
                                        @foreach ($nilai_per_periode as $tahun => $nilai_tahun)
                                            <div class="table-responsive">
                                                <h4>Periode Tahun: {{ $tahun }}</h4>
                                                <table id="add-row-{{ $tahun }}"
                                                    class="display table table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 6%">No</th>
                                                            <th>Periode</th>
                                                            @foreach ($v as $d)
                                                                <th>Bekerja Sama</th>
                                                                <th>Sesuai Tugas</th>
                                                                <th>Kepedulian</th>
                                                                <th>K3</th>
                                                                <th>Patuh Dan Taat</th>
                                                                <th>Tepat Waktu</th>
                                                                <th>Kesiapan</th>
                                                                <th>Apel Shift</th>
                                                                <th>Kelengkapan Atribut</th>
                                                            @endforeach
                                                            @if (auth()->user()->hasRole('penilai'))
                                                                <th>Aksi</th>
                                                            @endif
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php $no = 1; @endphp
                                                        @foreach ($nilai_tahun as $data)
                                                            <tr>
                                                                <td>{{ $no++ }}</td>
                                                                <td>{{ $data->periode->bulan }}</td>
                                                                <td>{{ $data->n1 }}</td>
                                                                <td>{{ $data->n2 }}</td>
                                                                <td>{{ $data->n3 }}</td>
                                                                <td>{{ $data->n4 }}</td>
                                                                <td>{{ $data->n5 }}</td>
                                                                <td>{{ $data->n6 }}</td>
                                                                <td>{{ $data->n7 }}</td>
                                                                <td>{{ $data->n8 }}</td>
                                                                <td>{{ $data->n9 }}</td>
                                                                @if (auth()->user()->hasRole('penilai'))
                                                                    <td>
                                                                        <button class="btn btn-link btn-success edit-button"
                                                                            data-toggle="modal" data-target="#addRowModal"
                                                                            data-id="{{ $data->id }}"
                                                                            data-bekerja_sama="{{ $data->n1 }}"
                                                                            data-sesuai_tugas="{{ $data->n2 }}"
                                                                            data-kepedulian="{{ $data->n3 }}"
                                                                            data-k3="{{ $data->n4 }}"
                                                                            data-patuh_dan_taat="{{ $data->n5 }}"
                                                                            data-tepat_waktu="{{ $data->n6 }}"
                                                                            data-kesiapan="{{ $data->n7 }}"
                                                                            data-apel_shift="{{ $data->n8 }}"
                                                                            data-kelengkapan_atribut="{{ $data->n9 }}">
                                                                            Input Nilai
                                                                        </button>
                                                                    </td>
                                                                @endif
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endforeach
                                        {{-- End Table --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Modals --}}
                    <div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header no-bd">
                                    <h5 class="modal-title">
                                        <span class="fw-mediumbold">
                                            Input Nilai Baru
                                        </span>
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form id="nilaiForm">
                                    <div class="card-body col-md-12">
                                        <input type="text" name="idadmin" value="" hidden id="idAdmin">
                                        <div class="form-group">
                                            <label for="bekerja_sama">Bekerja Sama dalam Team:</label>
                                            <input type="range" class="form-control" name="bekerja_sama" value="90"
                                                min="10" max="100"
                                                oninput="updateSliderValue('bekerja_sama_value', this.value)">
                                            <output id="bekerja_sama_value">90</output>
                                        </div>
                                        <div class="form-group">
                                            <label for="sesuai_tugas">Bekerja Sesuai Tugas:</label>
                                            <input type="range" class="form-control" name="sesuai_tugas"
                                                value="90" min="10" max="100"
                                                oninput="updateSliderValue('sesuai_tugas_value', this.value)">
                                            <output id="sesuai_tugas_value">90</output>
                                        </div>
                                        <div class="form-group">
                                            <label for="kepedulian">Kepedulian:</label>
                                            <input type="range" class="form-control" name="kepedulian" value="90"
                                                min="10" max="100"
                                                oninput="updateSliderValue('kepedulian_value', this.value)">
                                            <output id="kepedulian_value">90</output>
                                        </div>
                                        <div class="form-group">
                                            <label for="k3">K3:</label>
                                            <input type="range" class="form-control" name="k3" value="90"
                                                min="10" max="100"
                                                oninput="updateSliderValue('k3_value', this.value)">
                                            <output id="k3_value">90</output>
                                        </div>
                                        <div class="form-group">
                                            <label for="patuh_dan_taat">Patuh dan Taat:</label>
                                            <input type="range" class="form-control" name="patuh_dan_taat"
                                                value="90" min="10" max="100"
                                                oninput="updateSliderValue('patuh_dan_taat_value', this.value)">
                                            <output id="patuh_dan_taat_value">90</output>
                                        </div>
                                        <div class="form-group">
                                            <label for="tepat_waktu">Tepat Waktu:</label>
                                            <input type="range" class="form-control" name="tepat_waktu" value="90"
                                                min="10" max="100"
                                                oninput="updateSliderValue('tepat_waktu_value', this.value)">
                                            <output id="tepat_waktu_value">90</output>
                                        </div>
                                        <div class="form-group">
                                            <label for="kesiapan">Kesiapan:</label>
                                            <input type="range" class="form-control" name="kesiapan" value="90"
                                                min="10" max="100"
                                                oninput="updateSliderValue('kesiapan_value', this.value)">
                                            <output id="kesiapan_value">90</output>
                                        </div>
                                        <div class="form-group">
                                            <label for="apel_shift">Apel Shift:</label>
                                            <input type="range" class="form-control" name="apel_shift" value="90"
                                                min="10" max="100"
                                                oninput="updateSliderValue('apel_shift_value', this.value)">
                                            <output id="apel_shift_value">90</output>
                                        </div>
                                        <div class="form-group">
                                            <label for="kelengkapan_atribut">Kelengkapan Atribut:</label>
                                            <input type="range" class="form-control" name="kelengkapan_atribut"
                                                value="90" min="10" max="100"
                                                oninput="updateSliderValue('kelengkapan_atribut_value', this.value)">
                                            <output id="kelengkapan_atribut_value">90</output>
                                        </div>
                                    </div>
                                    <div class="modal-footer no-bd">
                                        <button id="saveButton" class="btn btn-primary">Simpan</button>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{-- End Modals --}}
                </div>
            </div>
        </div>
    </body>

    <script>
        $(document).ready(function() {
            //fuzzy
            $('#calculateFuzzy').on('click', function(event) {
                event.preventDefault(); // Menghentikan aksi default tombol jika diperlukan

                // Menampilkan loading
                $('#loading').show();

                // Melakukan request ke endpoint
                $.ajax({
                    url: $(this).attr('href'), // Mengambil URL dari atribut href tombol
                    method: 'GET', // Metode request, bisa disesuaikan jika POST diperlukan
                    success: function(response) {
                        // Menghentikan tampilan loading
                        $('#loading').hide();

                        // Menangani response jika perlu
                        alert('Perhitungan selesai.');
                        // Anda bisa melakukan redirect atau pembaruan tampilan jika diperlukan
                    },
                    error: function(xhr, status, error) {
                        // Menghentikan tampilan loading jika terjadi error
                        $('#loading').hide();

                        // Menangani error jika perlu
                        alert('Terjadi kesalahan: ' + error);
                    }
                });
            });
            $('#addRowModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var id = button.data('id'); // Extract info from data-* attributes
                var data = {
                    bekerja_sama: button.data('bekerja_sama'),
                    sesuai_tugas: button.data('sesuai_tugas'),
                    kepedulian: button.data('kepedulian'),
                    k3: button.data('k3'),
                    patuh_dan_taat: button.data('patuh_dan_taat'),
                    tepat_waktu: button.data('tepat_waktu'),
                    kesiapan: button.data('kesiapan'),
                    apel_shift: button.data('apel_shift'),
                    kelengkapan_atribut: button.data('kelengkapan_atribut')
                };

                // Update modal fields with extracted data
                $('#idAdmin').val(id);
                setSliderValues(data);
            });
        });

        function updateSliderValue(outputId, value) {
            document.getElementById(outputId).textContent = value;
        }

        // Function to update the value and color of the slider's output
        function updateValue(slider, valueSpan) {
            const value = slider.value;
            valueSpan.textContent = value;
            // Change the color based on value range
            if (value < 50) {
                valueSpan.style.color = 'red';
            } else if (value >= 50 && value < 75) {
                valueSpan.style.color = 'black';
            } else {
                valueSpan.style.color = 'green';
            }
        }

        // Function to set slider values from data
        function setSliderValues(data) {
            Object.keys(data).forEach(function(key) {
                const slider = document.querySelector(`input[name="${key}"]`);
                if (slider) {
                    slider.value = data[key];
                    const output = document.getElementById(`${key}_value`);
                    if (output) {
                        output.textContent = data[key];
                        updateValue(slider, output);
                    }
                }
            });
        }
        // Event listeners for each slider
        document.addEventListener('DOMContentLoaded', function() {
            const sliders = document.querySelectorAll('input[type="range"]');
            sliders.forEach((slider) => {
                const valueSpan = slider.nextElementSibling;
                slider.addEventListener('input', () => {
                    updateValue(slider, valueSpan);
                });

                // Initialize color and value
                updateValue(slider, valueSpan);
            });
        });

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Event handler for the edit button to populate modal with current data
            $('.edit-button').on('click', function() {
                const id = $(this).data('id');
                $('#idAdmin').val(id);

                // Fetch the current values for sliders and set them
                $.ajax({
                    url: `/admin/get_nilai/${id}`,
                    method: 'GET',
                    success: function(response) {
                        setSliderValues(response);
                    },
                    error: function(error) {
                        console.error('Error fetching slider values:', error);
                    }
                });
            });

            // Event handler for the save button to submit the form data
            $('#saveButton').on('click', function() {
                const formData = $('#nilaiForm').serialize();
                $.ajax({
                    url: '/admin/update_nilai',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#addRowModal').modal('hide');
                        // Optionally reload the page or update the table dynamically
                        // location.reload(); // Uncomment this line if you want to reload the page
                    },
                    error: function(error) {
                        console.error('Error updating values:', error);
                    }
                });
            });
        });
    </script>
@endsection
