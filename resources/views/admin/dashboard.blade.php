@extends('layouts.master')

@section('content')
    <style>
        .chart-container {
            width: 100%;
        }
    </style>
    <div class="main-panel">
        <div class="content">
            <div class="page-inner">
                <div class="page-header">
                    <b>
                        <h3 class="Penilaian Kinerja Karyawan">HALAMAN UTAMA
                    </b></h3>
                </div>

                <div class="row">
                    <div class="col-sm-6 col-md-3">
                        <div class="card card-stats card-round">
                            <div class="card-body ">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center icon-info bubble-shadow-small">
                                            <i class="far fa-clock"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ml-3 ml-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Periode Aktif</p>
                                            <h4 class="card-title">{{ $periode->bulan }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center icon-primary bubble-shadow-small">
                                            <i class="far fa-id-card"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ml-3 ml-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Jumlah Karyawan</p>
                                            <h4 class="card-title">{{ $karyawanCount }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                            <i class="far fa-folder-open"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ml-3 ml-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Jabatan</p>
                                            <h4 class="card-title">{{ Str::upper($jabatan) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center icon-warning bubble-shadow-small">
                                            <i class="far fa-user-circle"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ml-3 ml-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Jumlah Penilai</p>
                                            <h4 class="card-title">{{ $penilaiCount }}</h4> <!-- Updated line -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- ambil data periode pada filed bulan 2024-01-01 ambil tahunnya saja periode is_active==1 --}}
                    <select id="yearSelect" class="form-control">
                        @foreach ($years as $year)
                            <option value="{{ $year }}" {{ $year == '2024' ? 'selected' : '' }}>{{ $year }}
                            </option>
                        @endforeach
                    </select>


                    <div class="card chart-container">
                        <canvas id="chart"></canvas>
                    </div>
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
            const ctx = document.getElementById('chart').getContext('2d');
            let myChart; // Variable to hold the chart instance

            function updateChart(year) {
                $.ajax({
                    url: '/api/chart-data',
                    method: 'GET',
                    data: {
                        year: year
                    },
                    success: function(data) {
                        // Extract data for selected year
                        const selectedYearData = data[year];
                        const averagePerPeriode = selectedYearData.averagePerPeriode || Array(12).fill(
                            0);
                        const uninputCount = selectedYearData.uninputCount || Array(12).fill(0);
                        const bulanNames = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Aug",
                            "Sep", "Okt", "Nov", "Des"
                        ];

                        // Destroy the previous chart instance if it exists
                        if (myChart) {
                            myChart.destroy();
                        }

                        // Create a new chart
                        myChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: bulanNames,
                                datasets: [{
                                    label: 'Kinerja Karyawan Tahun Aktif: ' + year,
                                    backgroundColor: 'rgba(161, 198, 247, 1)',
                                    borderColor: 'rgb(47, 128, 237)',
                                    data: averagePerPeriode.map(value => Math.round(
                                        value)),
                                    tooltip: {
                                        enabled: true,
                                        callbacks: {
                                            label: function(tooltipItem) {
                                                const bulanIndex = tooltipItem
                                                    .dataIndex;
                                                const bulan = bulanNames[
                                                bulanIndex];
                                                return `Kinerja: ${tooltipItem.raw}% \nBelum Ternilai: ${uninputCount[bulanIndex]} Karyawan`;
                                            }
                                        }
                                    }
                                }]
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching data:', error);
                    }
                });
            }

            // Initialize chart with default year
            updateChart('2024');

            // Event listener for year selection
            $('#yearSelect').on('change', function() {
                const selectedYear = $(this).val();
                updateChart(selectedYear);
            });
        });
    </script>
@endsection
