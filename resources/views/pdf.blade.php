<!DOCTYPE html>
<html>

<head>
    <title>Data Hasil Penilaian Karyawan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="{{ asset('assets/img/icon.ico') }}" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="{{ asset('assets/js/plugin/webfont/webfont.min.js') }}"></script>


    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/azzara.min.css') }}">

    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css">

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href=" {{ asset('assets/css/demo.css') }}">
    {{-- <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script> --}}


    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }
        body {
            font-family: Arial, sans-serif;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .header .subtitle {
            margin: 0;
            font-size: 18px;
        }

        .header .address {
            margin: 0;
            font-size: 14px;
        }

        .kop-surat {
            border-bottom: 2px solid black;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }

        .signature-section {
            margin-top: 50px;
            text-align: right;
        }

        .signature-section .name {
            margin-top: 60px;
            font-weight: bold;
        }

        .signature-section .position {
            font-style: italic;
        }
    </style>
</head>

<body>
    <div class="header kop-surat">
        <p class="subtitle">Data Hasil Penilaian Karyawan</p>
        <p class="address">PTP Terminal Nonpetikemas Branch Cirebon</p>
        <p class="address">Jl. Perniagaan No.4, Panjunan, Kec. Lemahwungkuk, Kota Cirebon, Jawa Barat 45112</p>
    </div>
    {{-- <center>
        <h3>Hasil Keputusan Penilaian Karyawan Tahun 
    </center>
    </h3> --}}
    {{-- <center>
        <h1>Data Hasil Penilaian Karyawan
    </center>
    </h1> --}}

    {{-- <div class="px-2 py-8 max-w-xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center">
                <div class="text-gray-700 font-semibold text-lg">
                @php
                $p = $pic;
            @endphp
                <img src="<?php echo $pic; ?>" alt="" />
                </div>
            </div>
            <div class="text-gray-700">
                <div class="font-bold text-xl mb-2 uppercase">Invoice</div>
                <div class="text-sm">Date: 01/05/2023</div>
                <div class="text-sm">Invoice #: aa</div>
            </div>
        </div>
        <div class="border-b-2 border-gray-300 pb-8 mb-8">
            <h2 class="text-2xl font-bold mb-4">Bill To:</h2>
            <div class="text-gray-700 mb-2">cs</div>
            <div class="text-gray-700 mb-2">123 Main St.</div>
            <div class="text-gray-700 mb-2">Anytown, USA 12345</div>
            <div class="text-gray-700">johndoe@example.com</div>
        </div> --}}
    {{-- <img src="{{asset('assets/img/header.png')}}" alt="" /> --}}

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Jabatan</th>
                <th>Skor</th>
                <th>Hasil</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($nilai as $n)
                @php
                    $karyawanData = $karyawan->firstWhere('id', $n->id_karyawan);
                    $score = is_array($n->n_final) ? $n->n_final[0] : $n->n_final;

                @endphp
                @if ($karyawanData)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $karyawanData->nama_karyawan }}</td>
                        <td>{{ $karyawanData->jabatan }}</td>
                        <td>{{ $score }}</td>

                        <td>{{ $n->n_final >= 70 ? 'Rekomendasi' : 'Tidak Rekomendasi' }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>
<div class="signature-section">
    <p>Cirebon, {{ \Carbon\Carbon::now()->format('d M Y') }}</p>
    <p class="name">Hari Priyatna</p>
    <p class="position">Branch Manager</p>
</div>




</body>

</html>
