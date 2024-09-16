<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Penilaian Kinerja</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    {{-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script> --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        #loading {
            display: none;
            /* Styling loading indicator */
        }

        .tooltip-inner {
            background-color: black;
            /* Tooltip background color */
            color: white;
            /* Tooltip text color */
        }

        .tooltip-arrow {
            border-top-color: black;
            /* Tooltip arrow color */
        }

        .table-row-missing-data {
            background-color: #f8d7da;
            /* Light red background */
            color: #721c24;
            /* Dark red text color */
        }

        #noDataMessage {
            display: none;
            color: red;
        }

        .loader {
            width: 100vw;
            height: 100vh;
            background: #fff;
            position: fixed;
            top: 0;
            left: 0;
        }

        .loader-inner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }


        /* Spinner */
        .lds-roller {
            display: inline-block;
            position: relative;
            width: 64px;
            height: 64px;
        }

        .lds-roller div {
            animation: lds-roller 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
            transform-origin: 32px 32px;
        }

        .lds-roller div:after {
            content: " ";
            display: block;
            position: absolute;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #333;
            margin: -3px 0 0 -3px;
        }

        .lds-roller div:nth-child(1) {
            animation-delay: -0.036s;
        }

        .lds-roller div:nth-child(1):after {
            top: 50px;
            left: 50px;
        }

        .lds-roller div:nth-child(2) {
            animation-delay: -0.072s;
        }

        .lds-roller div:nth-child(2):after {
            top: 54px;
            left: 45px;
        }

        .lds-roller div:nth-child(3) {
            animation-delay: -0.108s;
        }

        .lds-roller div:nth-child(3):after {
            top: 57px;
            left: 39px;
        }

        .lds-roller div:nth-child(4) {
            animation-delay: -0.144s;
        }

        .lds-roller div:nth-child(4):after {
            top: 58px;
            left: 32px;
        }

        .lds-roller div:nth-child(5) {
            animation-delay: -0.18s;
        }

        .lds-roller div:nth-child(5):after {
            top: 57px;
            left: 25px;
        }

        .lds-roller div:nth-child(6) {
            animation-delay: -0.216s;
        }

        .lds-roller div:nth-child(6):after {
            top: 54px;
            left: 19px;
        }

        .lds-roller div:nth-child(7) {
            animation-delay: -0.252s;
        }

        .lds-roller div:nth-child(7):after {
            top: 50px;
            left: 14px;
        }

        .lds-roller div:nth-child(8) {
            animation-delay: -0.288s;
        }

        .lds-roller div:nth-child(8):after {
            top: 45px;
            left: 10px;
        }

        @keyframes lds-roller {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <!--
   Tip 1: You can change the background color of the main header using: data-background-color="blue | purple | light-blue | green | orange | red"
  -->
        <div class="main-header" data-background-color="light-blue">
            <!-- Logo Header -->
            <div class="logo-header">

                <a href="" class="logo">
                    <img src="{{ asset('assets/img/logoptp.png') }}" alt="Logo"
                        style="max-width: 50%; height: auto;">
                </a>
                <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse"
                    data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon">
                        <i class="fa fa-bars"></i>
                    </span>
                </button>
                <button class="topbar-toggler more"><i class="fa fa-ellipsis-v"></i></button>
                <div class="navbar-minimize">
                    <button class="btn btn-minimize btn-rounded">
                        <i class="fa fa-bars"></i>
                    </button>
                </div>
            </div>
            <!-- End Logo Header -->

            <!-- Navbar Header -->
            <nav class="navbar navbar-header navbar-expand-lg">

                <div class="container-fluid">
                    <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
                        <li class="nav-item dropdown hidden-caret">
                            <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#"
                                aria-expanded="false">
                                <div class="avatar-sm">
                                    <img src="{{ asset('assets/img/profil/CV.jpg ') }}" alt="..."
                                        class="avatar-img rounded-circle">
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-user animated fadeIn">
                                <li>
                                    <div class="user-box">
                                        <div class="u-text">
                                            <h4>{{ Auth::user()->role }} || {{ Auth::user()->name }}</h4>
                                            <p class="text-muted">{{ Auth::user()->email }}</p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="dropdown-divider"></div>
                                    {{-- <a class="dropdown-item" href="">Profil Saya</a>
                                    <div class="dropdown-divider"></div> --}}
                                    <!-- <a class="dropdown-item" href="">Pengaturan Akun</a>
                                    <div class="dropdown-divider"></div> -->
                                    <a class="dropdown-item" href="#" data-toggle="modal"
                                        data-target="#logoutModal">Logout</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- End Navbar -->
        </div>

        <!-- Sidebar -->
        <div class="sidebar">

            <div class="sidebar-background"></div>
            <div class="sidebar-wrapper scrollbar-inner">
                <div class="sidebar-content">
                    <div class="user">
                        <div class="avatar-sm float-left mr-2">
                            {{-- <img src="{{asset('assets/img/profil/CV.jpg')}}" alt="..." class="avatar-img rounded-circle"> --}}
                        </div>
                        <div class="info">

                        </div>
                    </div>
                    <ul class="nav">
                        @if (Auth::user()->role === 'hrd')
                            <li class="nav-item">
                                <a href="/admin/">
                                    <i class="fas fa-home"></i>

                                    <p>Halaman Utama</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/admin/periode">
                                    <i class="fas fa-calendar"></i>

                                    <p>Periode</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/admin/kriteria">
                                    <i class="fas fa-list"></i>

                                    <p>Kriteria
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/admin/jabatan">
                                    <i class="fas fa-book"></i>

                                    <p>Data Jabatan & Bidang</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="/admin/datakaryawann">
                                    <i class="fas fa-users"></i>

                                    <p>Data Karyawan</p>
                                </a>
                            </li>
                        @else()
                            <li class="nav-item">
                                <a href="/admin/datakaryawann">
                                    <i class="fas fa-calculator"></i>

                                    <p>Nilai Karyawan</p>
                                </a>
                            </li>
                        @endif
                        @if (Auth::user()->role == 'manager' || Auth::user()->role == 'penilai')
                            <li class="nav-item">
                                <a href="/admin/hasil">
                                    <i class="fas fa-book"></i>

                                    <p>Hasil</p>
                                </a>
                            </li>
                        @endif
                    </ul>


                </div>
            </div>
        </div>
        <!-- End Sidebar -->
        @yield('content')
        {{-- <div class="main-panel">
            <div class="content">
            </div>
        </div> --}}

        <!-- Logout Modal-->
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Anda yakin akan Logout??</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    {{-- <div class="modal-body">Anda yakin akan Logout?</div> --}}
                    <div class="modal-footer">
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--   Core JS Files   -->

    <script src="{{ asset('assets/js/core/jquery.3.2.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>

    <!-- jQuery UI -->
    <script src="{{ asset('assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js') }} "></script>
    <script src="{{ asset('assets/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js') }} "></script>

    <!-- jQuery Scrollbar -->
    <script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }} "></script>

    <!-- Moment JS -->
    <script src="{{ asset('assets/js/plugin/moment/moment.min.js') }} "></script>

    <!-- Chart JS -->
    <script src="{{ asset('assets/js/plugin/chart.js/chart.min.js') }} "></script>

    <!-- jQuery Sparkline -->
    <script src="{{ asset('assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js') }} "></script>

    <!-- Chart Circle -->
    <script src="{{ asset('assets/js/plugin/chart-circle/circles.min.js') }} "></script>

    <!-- Datatables -->
    <script src="{{ asset('assets/js/plugin/datatables/datatables.min.js') }} "></script>

    <!-- DataTables JavaScript -->
    {{-- <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script> --}}
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js">
    </script>
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js">
    </script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.print.min.js">
    </script>

    <!-- Bootstrap Notify -->
    <script src="{{ asset('assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }} "></script>

    <!-- Bootstrap Toggle -->
    <script src="{{ asset('assets/js/plugin/bootstrap-toggle/bootstrap-toggle.min.js') }} "></script>

    <!-- jQuery Vector Maps -->
    <script src="{{ asset('assets/js/plugin/jqvmap/jquery.vmap.min.js') }} "></script>
    <script src="{{ asset('assets/js/plugin/jqvmap/maps/jquery.vmap.world.js') }} "></script>

    <!-- Google Maps Plugin -->
    <script src="{{ asset('assets/js/plugin/gmaps/gmaps.js') }} "></script>

    <!-- Sweet Alert -->
    <script src="{{ asset('assets/js/plugin/sweetalert/sweetalert.min.js') }} "></script>

    <!-- Azzara JS -->
    <script src="{{ asset('assets/js/ready.min.js') }} "></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script script type="text/javascript">
        @if (Session::has('message'))
            var type = "{{ Session::get('alert-type', 'info') }}";
            switch (type) {
                case 'info':
                    toastr.info("{{ Session::get('message') }}");
                    break;
                case 'warning':
                    toastr.warning("{{ Session::get('message') }}");
                    break;
                case 'success':
                    toastr.success("{{ Session::get('message') }}");
                    break;
                case 'error':
                    toastr.error("{{ Session::get('message') }}");
                    break;
            }
        @endif
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.canvasjs.com/ga/canvasjs.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            // cari karyawan
            $('#search').on('input', function() {
                let search = $(this).val();

                $.ajax({
                    url: '{{ route('karyawan_index') }}',
                    method: 'GET',
                    data: {
                        search: search
                    },
                    success: function(response) {
                        if (response.karyawan.data.length === 0) {
                            $('#karyawan-table-body').html(
                                '<tr><td colspan="6">Data Tidak ditemukan.</td></tr>');
                        } else {
                            updateTable(response.karyawan.data, response.karyawan.current_page);
                        }
                    },
                    error: function() {
                        console.log('Error fetching data.');
                    }
                });
            });

            function updateTable(karyawan, currentPage) {
                let tableBody = $('#karyawan-table-body');
                tableBody.empty();

                let startNumber = (currentPage - 1) * 10 + 1; // Assuming 10 items per page

                karyawan.forEach((data, index) => {
                    let row = `
                <tr>
                    <td>${startNumber++}</td>
                    <td>
                        <a href="/admin/datakaryawan/${data.id}" style="color:black;" data-toggle="tooltip"
                            title="${data.tooltip ? data.tooltip : ''}">
                            ${data.nama_karyawan}
                        </a>
                    </td>
                    <td>${data.nrp}</td>
                    <td>${data.bidang}</td>
                    <td>${data.jabatan}</td>
                    <td>
                        <button class="btn btn-link btn-success edit-button">
                            <a href="/admin/datakaryawan/${data.id}">Detail</a>
                        </button>
                    </td>
                </tr>
            `;
                    tableBody.append(row);
                });
            }
            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip({
                trigger: 'manual' // Set trigger to 'manual' to control tooltip visibility programmatically
            });

            // Show all tooltips immediately
            $('[data-toggle="tooltip"]').each(function() {
                $(this).tooltip('show');
            });

            // Optionally hide tooltips after 2 seconds (2000 milliseconds)
            setTimeout(function() {
                $('[data-toggle="tooltip"]').tooltip('hide');
            }, 1000);
        });
    </script>
    {{-- <script type="text/javascript">
        const ctx = document.getElementById("chart").getContext('2d');
        const averagePerPeriode = @json(isset($averagePerPeriode) ? array_values($averagePerPeriode) : array_fill(0, 12, 0));
        const roundedAveragePerPeriode = averagePerPeriode.map(value => Math.round(value));
        const uninputCount = @json(isset($uninputCount) ? array_values($uninputCount) : array_fill(0, 12, 0));
        const bulanNames = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Des"];
        const tahunActive = @json(isset($datak) ? $datak : 'kosong')

        const tahunActiveYear = tahunActive ? new Date(tahunActive).getFullYear() : 'kosong';



        const myChart = new Chart(ctx, {

            type: 'bar',
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr",
                    "Mei", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Des"
                ],
                datasets: [{

                    label: 'Kinerja Karyawan Tahun Aktif :' + tahunActiveYear,
                    backgroundColor: 'rgba(161, 198, 247, 1)',
                    borderColor: 'rgb(47, 128, 237)',
                    // perbulan
                    data: roundedAveragePerPeriode,
                    tooltip: {
                        enabled: true,
                        callbacks: {
                            label: function(tooltipItem) {
                                const bulanIndex = tooltipItem.dataIndex; // Get the index of the bar
                                const bulan = bulanNames[bulanIndex]; // Get the month name by index

                                return `Kinerja: ${tooltipItem.raw}% \nBelum Ternilai: ${uninputCount[bulanIndex]} Karyawan`;

                            }
                        }
                    }
                }]





            },
        });
    </script> --}}

</body>

</html>
