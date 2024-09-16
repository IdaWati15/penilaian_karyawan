@extends('layouts.master')

@section('content')

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
                                <a href="/admin/datakaryawann">Perhitungan Fuzzy</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-body">
                                    <div class="container">
                                        <b>
                                            <h3> Hasil Perhitungan Fuzzy Mamdani
                                        </b></h3>
                                        <div class="table-responsive">
                                            <table id="fuzzy-results" class="display table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Kriteria Variabel</th>
                                                        <th>Nilai</th>
                                                        <th>Titik Derajat Keanggotaan</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($averages as $variable => $average)
                                                    <tr>
                                                        <td>{{ $variable }}</td>
                                                        <td>{{ round($average, 2) }}</td>
                                                        <td>
                                                            @if (isset($fuzzySets[$variable]) && !empty($fuzzySets[$variable]))
                                                                @php
                                                                    // Determine the maximum value and the corresponding set
                                                                    $maxValue = max($fuzzySets[$variable]);
                                                                @endphp
                                                                @foreach ($fuzzySets[$variable] as $set => $value)
                                                                    <div>
                                                                        <strong>{{ $set }}:</strong>
                                                                        {{ round($value, 2) }}
                                                                        @if ($value == $maxValue)
                                                                            <span style="color: green; font-weight: bold;">
                                                                                &#10003;</span> <!-- Checkmark -->
                                                                        @endif
                                                                    </div>
                                                                @endforeach
                                                            @else
                                                                <em>No fuzzy sets available for {{ $variable }}</em>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach

                                                </tbody>
                                            </table>

                                        </div>

                                        <br>

                                        <b>
                                            <h3> Keputusan Akhir
                                        </b></h3>
                                        <div class="table-responsive">
                                            <table id="final-decision" class="display table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Nilai</th>
                                                        <th>Keputusan</th>
                                                        <th>Derajat keanggotaan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {{-- @foreach ($results as $result) --}}
                                                    <tr>
                                                        {{-- <td>{{ round($result['Nilai_Output'], 2) }}</td> --}}
                                                        <td>{{ number_format($postDataResult, 2) }}</td>
                                                        <td>
                                                            @if ($postDataResult > 71)
                                                                Rekomendasi
                                                            @else
                                                                Tidak Rekomendasi
                                                                {{-- <div class="derajat-keanggotan" id="derajat-keanggotan"> --}}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="derajat-keanggotan" id="derajat-keanggotan">
                                                            </div>
                                                        </td>

                                                    </tr>
                                                    {{-- @endforeach --}}

                                                </tbody>
                                            </table>
                                        </div>

                                        <br>
                                        <b>
                                            <h3> Hasil Keputusan :
                                        </b></h3>
                                        <h4>Jika hasil keputusan dari perhitungan fuzzy mamdani sebesar 10-70 artinya
                                            Karyawan "Tidak Rekomendasi"</h4>
                                        <h4>Jika hasil keputusan dari perhitungan fuzzy mamdani sebesar 70-100 artinya
                                            Karyawan "Direkomendasi"</h4>
                                        <br>
                                        <b>
                                            <h3> Keterangan :
                                                {{-- {{$aggregatedResults[0]}} --}}
                                        </b></h3>
                                        <h4 id='decision' data-id="{{ number_format($postDataResult, 2) }}">Berdasarkan
                                            hasil perhitungan, diperoleh nilai hasil keputusan sebesar
                                            {{ number_format($postDataResult, 2) }} yang artinya karyawan tersebut
                                            dinyatakan <b>
                                                @if ($postDataResult > 70)
                                                    Rekomendasi
                                                @else
                                                    Tidak Rekomendasi
                                                @endif
                                            </b> untuk dijadikan karyawan tetap
                                        </h4>
                                        <div id="container" style="height: 300px; width: 100%;"></div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </body>
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>

    <script>
        var decisionElement = document.getElementById('decision');

        var nilai = parseFloat(decisionElement.getAttribute('data-id')); // Extract and convert to number
        var kategori = nilai <= 70 ? 'Kurang' : 'Rekomendasi';
        var trapezoidMF1 = [{
                x: 0,
                y: 0
            }, // Titik kiri bawah
            {
                x: 10,
                y: 1
            }, // Titik kiri atas
            {
                x: 70,
                y: 1
            }, // Titik kanan atas
            {
                x: 70,
                y: 0
            } // Titik kanan bawah
        ];

        var trapezoidMF2 = [{
                x: 70,
                y: 0
            }, // Titik kiri bawah
            {
                x: nilai,
                y: 1
            }, // Titik kiri atas
            {
                x: 100,
                y: 1
            }, // Titik kanan atas
            {
                x: 110,
                y: 0
            } // Titik kanan bawah
        ];
        if (nilai > 70) {
            // Update trapezoidMF2 based on the value of 'nilai'


            // Ensure trapezoidMF1 stays unchanged
            trapezoidMF1 = [{
                    x: 0,
                    y: 0
                }, // Titik kiri bawah
                {
                    x: 10,
                    y: 1
                }, // Titik kiri atas
                {
                    x: 55,
                    y: 1
                }, // Titik kanan atas
                {
                    x: nilai,
                    y: 0
                } // Titik kanan bawah
            ];
        } else {
            // Update trapezoidMF1 based on the value of 'nilai'

            // Ensure trapezoidMF2 stays unchanged
            trapezoidMF2 = [{
                    x: 70,
                    y: 0
                }, // Titik kiri bawah
                {
                    x: nilai,
                    y: 1
                }, // Titik kiri atas
                {
                    x: 100,
                    y: 1
                }, // Titik kanan atas
                {
                    x: 110,
                    y: 0
                } // Titik kanan bawah
            ];
        }

        // Create a new CanvasJS chart
        var chart = new CanvasJS.Chart("container", {
            theme: "light2", // "light1", "light2", "dark1", "dark2"
            animationEnabled: true,
            zoomEnabled: true,
            title: {
                text: "Graphic Keputusan "
            },
            axisX: {
                title: "Nilai",
                minimum: 0,
                maximum: 100
            },
            axisY: {
                title: "Derajat Keanggotaan",
                minimum: 0,
                maximum: 1
            },
            data: [{
                    type: "area",
                    name: "Tidak Rekomendasi",
                    showInLegend: true,
                    dataPoints: trapezoidMF1
                },
                {
                    type: "area",
                    name: "Rekomendasi",
                    showInLegend: true,
                    dataPoints: trapezoidMF2
                },
                {
                    type: "scatter",
                    name: "Nilai Keputusan",
                    showInLegend: true,
                    dataPoints: [{
                        x: nilai,
                        y: 1
                    }]
                }
            ]
        });

        // Function to add dynamic data points
        function addDataPoints(noOfDps) {
            var xVal = chart.options.data[0].dataPoints.length + 1,
                yVal = 100;
            for (var i = 0; i < noOfDps; i++) {
                yVal = yVal + Math.round(5 + Math.random() * (-5 - 5));
                chart.options.data[0].dataPoints.push({
                    x: xVal,
                    y: yVal
                });
                xVal++;
            }
        }
        // Function to calculate membership degree (y) based on x (nilai)
        function calculateMembershipDegree(trapezoid, xValue) {
            var yValue = 0;

            if (xValue <= trapezoid[0].x || xValue >= trapezoid[3].x) {
                yValue = 0;
            } else if (xValue >= trapezoid[1].x && xValue <= trapezoid[2].x) {
                yValue = 1;
            } else if (xValue > trapezoid[0].x && xValue < trapezoid[1].x) {
                yValue = (xValue - trapezoid[0].x) / (trapezoid[1].x - trapezoid[0].x);
            } else if (xValue > trapezoid[2].x && xValue < trapezoid[3].x) {
                yValue = (trapezoid[3].x - xValue) / (trapezoid[3].x - trapezoid[2].x);
            }

            return yValue;
        }


        chart.render();
        var yValue1 = calculateMembershipDegree(trapezoidMF1, nilai);
        var yValue2 = calculateMembershipDegree(trapezoidMF2, nilai);

        console.log('Derajat Keanggotaan tidak:', yValue1);
        console.log('Derajat Keanggotaan Rekomendasi:', yValue2);
        document.getElementById('derajat-keanggotan').innerHTML = `
    <p>Tidak Rekomendasi: ${yValue1.toFixed(2)} ${yValue1 === 1 ? '✔️' : ''}</p>
    <p>Rekomendasi: ${yValue2.toFixed(2)} ${yValue2 === 1 ? '✔️' : ''}</p>
`;
    </script>
@endsection
