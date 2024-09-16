@extends('layouts.master')
@section('content')
    <div class="main-panel">
        <div class="content">
            <div class="page-inner">
                <div class="page-header">
                </div>
                <div class="col-md-12">
                    <div class="d-flex align-items-center justify-content-between">
                        <!-- Move the select box to the left -->


                        <!-- Title on the right -->
                        <h4 class="card-title mr-auto">Data Hasil Perhitungan Fuzzy</h4>
                        <div class="">
                            <div class="row">
                                {{-- <i class="fas fa-search pr-2 pt-3"></i> --}}
                                <input type="text" class="form-control" style="width:200px;margin-left:15px"
                                    name="search" placeholder="cari nama karyawan" id="search">

                            </div>
                            <select id="yearSelect" class="form-control" style="width:200px;">
                                @foreach ($years as $year)
                                    <option value="{{ $year }}" {{ $year == '2024' ? 'selected' : '' }}>
                                        {{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- PDF download button -->
                        @if (Auth::user()->role == 'manager')
                            <a href="{{ route('export.pdf') }}" class="btn btn-secondary btn-round ml-2 download-btn">
                                <i class="fa fa-download"></i> Download
                            </a>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="add-row-hasil" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Jabatan</th>
                                        <th>Skor</th>
                                        <th>Hasil</th>
                                    </tr>
                                </thead>
                                {{-- <tbody id="dataBody">
                                    @foreach ($nilai as $n)
                                        @php
                                            $karyawanData = $karyawan->firstWhere('id', $n->id_karyawan);
                                        @endphp
                                        @if ($karyawanData)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $karyawanData->nama_karyawan }}</td>
                                                <td>{{ $karyawanData->jabatan }}</td>
                                                @if ($n->n_final == null)
                                                @else
                                                    <td>{{ $n->n_final }}</td>
                                                @endif
                                                <td>{{ $n->n_final >= 55 ? 'Rekomendasi' : 'Tidak Rekomendasi' }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody> --}}
                                <tbody id="dataBody">
                                    @foreach ($nilai as $n)
                                        @php
                                            $karyawanData = $karyawan->firstWhere('id', $n->id_karyawan);
                                        @endphp
                                        @if ($karyawanData && !is_null($n->n_final))
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $karyawanData->nama_karyawan }}</td>
                                                <td>{{ $karyawanData->jabatan }}</td>
                                                <td>{{ $n->n_final }}</td>
                                                <td>{{ $n->n_final >= 55 ? 'Rekomendasi' : 'Tidak Rekomendasi' }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>



                            </table>
                            <div id="noDataMessage" style="left:50%;padding-left:40%;padding-top:10%">Data Tidak Ditemukan.
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
    </div>
    <script>
        $(document).ready(function() {
            function filterTable() {
                var input = $('#search').val().toLowerCase();
                var rows = $('#dataBody tr');
                var isDataFound = false;

                rows.each(function() {
                    var rowText = $(this).text().toLowerCase();
                    var isVisible = rowText.indexOf(input) > -1;
                    $(this).toggle(isVisible);
                    if (isVisible) {
                        isDataFound = true;
                    }
                });

                // Show or hide the "Data Tidak Ditemukan" message
                $('#noDataMessage').toggle(!isDataFound);
            }

            $('#search').on('keyup', function() {
                filterTable();
            });
            // Ambil tahun yang dipilih dari elemen select
            const selectedYear = $('#yearSelect').val();

            // Fungsi untuk memuat data berdasarkan tahun
            function loadData(year) {
                $.ajax({
                    url: `/api/getChartData`,
                    type: 'GET',
                    data: {
                        year: year
                    },
                    success: function(data) {
                        const tbody = $('#dataBody');
                        tbody.empty(); // Bersihkan data lama

                        if (data[year]) {
                            data[year].forEach((item, index) => {
                                const nFinal = item.n_final !== null ? item.n_final :
                                    'Belum Hitung';
                                const recommendation = item.n_final !== null && item.n_final >=
                                    55 ? 'Rekomendasi' : 'Tidak Rekomendasi';


                                tbody.append(`
                           <tr>
                                <td>${index + 1}</td>
                                <td>${item.nama_karyawan}</td>
                                <td>${item.jabatan}</td>
                                <td>${nFinal}</td>
                                <td>${item.n_final !== null ? recommendation : 'Belum Dihitung'}</td>
                            </tr>
                        `);
                            });
                        } else {
                            tbody.append(`
                            <tr>
                                <td colspan="5">No data available for the selected year.</td>
                            </tr>
                        `);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching data:', error);
                    }
                });
            }

            // Muat data untuk tahun yang dipilih saat halaman dimuat
            loadData(selectedYear);

            // Tangani perubahan tahun pada elemen select
            $('#yearSelect').on('change', function() {
                const selectedYear = $(this).val();
                loadData(selectedYear);
            });
        });
    </script>
@endsection
