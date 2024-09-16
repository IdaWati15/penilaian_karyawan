@extends('layouts.master')
@section('content')
    <div class="main-panel">


        <div class="content">
            <div class="page-inner">
                <div class="page-header">
                    <ul class="breadcrumbs">
                        <li class="nav-home">
                            <a href="#">
                                <i class="fas fa-home"></i>
                            </a>
                        </li>
                        <li class="separator">
                            <i class="flaticon-right-arrow"></i>
                        </li>
                        <li class="nav-item">
                            <a href="">Data Karyawan</a>
                        </li>


                    </ul>
                </div>
                <div class="col md-12">
                    <div class="card">


                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">

                                {{-- <label for="nama">Nama</label> --}}
                                 @if (auth()->user()->hasRole('hrd'))
                                <i class="fas fa-search pr-2"></i>
                                @else
                                <p></p>
                                @endif

                                <input type="text" class="form-control col-md-4" name="search"
                                    placeholder="cari nama karyawan" id="search">


                                @if (auth()->user()->hasRole('hrd'))
                                <button class="btn btn-primary btn-round ml-auto" data-toggle="modal"
                                    data-target="#addRowModal">
                                    <i class="fa fa-plus"></i>
                                    Tambah
                                </button>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            {{-- modals --}}
                            <div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header no-bd">
                                            <h5 class="modal-title">
                                                <span class="fw-mediumbold">
                                                    Tambah Data Karyawan
                                                </span>
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <form method="post" action="/admin/datakaryawan/postkaryawan"
                                            id="dataKaryawanForm">
                                            @csrf
                                            <input type="hidden" name="_method" id="formMethod" value="POST">
                                            <input type="hidden" name="karyawan_id" id="karyawan_id" value="">
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-sm-12">



                                                        <div class="form-group form-group-default">
                                                            <label for="nama">Nama</label>
                                                            <input type="text" class="form-control" id="nama_karyawan"
                                                                id="nama_karyawan" name="nama_karyawan">

                                                        </div>

                                                        <div class="form-group">
                                                            <label for="bidang">Bidang</label>
                                                            <select class="form-control" id="bidang" name="bidang">
                                                                <option value="" disabled selected>Pilih Bidang
                                                                </option>
                                                                <option value="Operasi">Operasi</option>
                                                                <option value="Pendukung Operasi">Pendukung Operasi</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="jabatan">Jabatan</label>
                                                            <select class="form-control" id="jabatan" name="jabatan">
                                                                <option value="" disabled selected>Pilih Jabatan
                                                                </option>
                                                                <option value="TI">TI</option>
                                                                <option value="Dev Ops">Dev Ops</option>
                                                                <option value="Database maintenance">Database maintenance
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group form-group-default">
                                                            <label for="nama">NRP</label>
                                                            <input type="number" class="form-control" id="nrp"
                                                                name="nrp">

                                                        </div>




                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal-footer no-bd">
                                                <button type="submit" id="addRowButton"
                                                    class="btn btn-primary">Simpan</button>
                                                <button type="button" class="btn btn-danger"
                                                    data-dismiss="modal">Tutup</button>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="add-row" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 6%">No</th>
                                            <th>Nama Karyawan</th>
                                            <th>NRP</th>
                                            <th>Bidang</th>
                                            <th>Jabatan</th>
                                            <th style="width: 10%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="karyawan-table-body">
                                        @php
                                            $startNumber =
                                                isset($karyawan) &&
                                                $karyawan instanceof \Illuminate\Pagination\LengthAwarePaginator
                                                    ? ($karyawan->currentPage() - 1) * $karyawan->perPage() + 1
                                                    : 1;
                                        @endphp

                                        @forelse ($karyawan as $index => $data)
                                            <tr>
                                                <td>{{ $karyawan->firstItem() + $index }}</td>
                                                <td>
                                                    <a href="/admin/datakaryawan/{{ $data->id }}" style="color:black;"
                                                        data-toggle="tooltip"
                                                        title="{{ $karyawanWithAllNullValues->contains($data) ? 'Data belum terisi' : '' }}">
                                                        {{ $data->nama_karyawan }}
                                                    </a>
                                                </td>
                                                <td>{{ $data->nrp }}</td>
                                                <td>{{ $data->bidang }}</td>
                                                <td>{{ $data->jabatan }}</td>
                                                @if (auth()->user()->hasRole('hrd'))
                                                <td>
                                                    <button class="btn btn-link btn-success edit-button" data-id="{{ $data->id }}" data-nama="{{ $data->nama_karyawan }}" data-nrp="{{ $data->nrp }}" data-bidang="{{ $data->bidang }}" data-jabatan="{{ $data->jabatan }}">
                                                        <a>Edit</a>
                                                    </button>
                                                </td>
                                                @endif
                                                <td>
                                                    <button class="btn btn-link btn-success">
                                                        <a href="/admin/datakaryawan/{{ $data->id }}">Detail</a>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6">No data found.</td>
                                            </tr>
                                        @endforelse

                                    </tbody>
                                </table>
                                {{ $karyawan->links() }}
                                {{-- @if ($karyawan instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                    {{ $karyawan->links() }}
                                @endif --}}

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
            function updateJabatanOptions(bidang, selectedJabatan) {
                var jabatanSelect = $('#jabatan');
                jabatanSelect.empty(); // Clear current options
    
                // Define options based on bidang
                var options = [];
                if (bidang === 'Operasi') {
                    options = [
                        'Operator Alat (Forklift)',
                        'Operator Alat (Excavator)',
                        'Operator Alat (Wheel Loader)',
                        'Adm Kapal Gudang dan Lapangan',
                        'Pelaksana Terminal Operation 1',
                        'Pelaksana Terminal Operation 2',
                        'Rigger',
                        'Operator Dump Truck',
                        'Pelaksanaan Perencanaan dan Pengendalian Kapal'
                    ];
                } else if (bidang === 'Pendukung Operasi') {
                    options = [
                        'Adm Pembendaharaan',
                        'Adm Akuntansi'
                    ];
                }
    
                options.forEach(function(option) {
                    var optionElement = $('<option>').attr('value', option).text(option);
                    if (option === selectedJabatan) {
                        optionElement.attr('selected', 'selected'); // Select the saved jabatan
                    }
                    jabatanSelect.append(optionElement);
                });
            }
    
            $('.edit-button').on('click', function() {
                var id = $(this).data('id');
                var nama = $(this).data('nama');
                var nrp = $(this).data('nrp');
                var bidang = $(this).data('bidang');
                var jabatan = $(this).data('jabatan');
    
                $('#dataKaryawanForm').attr('action', '/admin/datakaryawan/update/' + id);
                $('#formMethod').val('PUT');
                $('#nama_karyawan').val(nama);
                $('#nrp').val(nrp);
                $('#bidang').val(bidang);
                $('#jabatan').val(jabatan);
                
                // Update jabatan options
                updateJabatanOptions(bidang, jabatan);
    
                $('#karyawan_id').val(id);
                $('#addRowModal').modal('show');
            });
    
            $('#updateButton').on('click', function() {
                var id = $('#karyawan_id').val();
                var nama = $('#nama_karyawan').val();
                var nrp = $('#nrp').val();
                var bidang = $('#bidang').val();
                var jabatan = $('#jabatan').val();
    
                $.ajax({
                    url: '/admin/datakaryawan/update/' + id,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'PUT',
                        nama_karyawan: nama,
                        nrp: nrp,
                        bidang: bidang,
                        jabatan: jabatan
                    },
                    success: function(response) {
                        $('#addRowModal').modal('hide');
                        window.location.reload(); // Refresh the page
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });
    
            $('#bidang').on('change', function() {
                const selectedBidang = $(this).val();
                updateJabatanOptions(selectedBidang);
            });
    
            $('#jabatan').on('change', function() {
                const randomSixDigitNumber = Math.floor(100000 + Math.random() * 900000);
                $('#nrp').val(randomSixDigitNumber);
            });
        });
    </script>
    
@endsection