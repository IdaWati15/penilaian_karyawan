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
                            <a href="">Periode</a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="card-title">Periode</h4>
                                <button class="btn btn-primary ml-auto" data-toggle="modal" data-target="#addRowModal">
                                    <i class="fa fa-plus"></i> Tambah Periode
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            {{-- Add Periode Modal --}}
                            <div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header no-bd">
                                            <h5 class="modal-title">
                                                <span class="fw-mediumbold">Input Periode Baru</span>
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form method="post" action="{{ route('periode.input') }}">
                                            @csrf
                                            <input type="hidden" name="_method" id="formMethod" value="POST">
                                            <input type="hidden" name="karyawan_id" id="karyawan_id" value="">
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label for="periode">Periode Baru</label>
                                                            <input type="date" class="form-control" id="periode"
                                                                name="periode">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="alertMessage" class="alert alert-danger" style="display: none;">
                                                    Periode dengan bulan dan tahun yang sama sudah ada di database.
                                                </div>
                                                <div id="newYearAlert" class="alert alert-info"
                                                    style="display:none; color: blue;"></div>
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

                            {{-- Periode Table --}}
                            <div class="table-responsive">
                                <table id="add-row" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 6%">No</th>
                                            <th>Periode</th>
                                            <th>Aktif</th>
                                            <th style="width: 10%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $currentPage = $periode->currentPage();
                                            $perPage = $periode->perPage();
                                            $startNumber = ($currentPage - 1) * $perPage + 1;
                                        @endphp
                                        @foreach ($periode as $index => $data)
                                            <tr>
                                                <td>{{ $startNumber++ }}</td>
                                                <td>{{ \Carbon\Carbon::parse($data->bulan)->format('F Y') }}</td>
                                                <td>{{ $data->is_active ? 'Aktif' : '-' }}</td>
                                                <td>
                                                    <button class="btn btn-link btn-success edit-button"
                                                        data-id="{{ $data->id }}" data-bulan="{{ $data->bulan }}"
                                                        data-toggle="modal" data-target="#addRowModal">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $periode->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Check if periode exists
            $('#periode').change(function() {
                var periode = $(this).val();
                $.ajax({
                    url: '{{ route('periode.check') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        periode: periode
                    },
                    success: function(response) {
                        if (response.isNewYear) {
                            $('#newYearAlert').text('Periode tahu baru !').show();
                            $('#alertMessage').hide();
                            $('#addRowButton').prop('disabled', false);
                        } else {
                            $('#newYearAlert').hide();
                            if (response.exists) {
                                $('#alertMessage').text(
                                    'Periode dengan bulan dan tahun yang sama sudah ada di database.!'
                                ).show();
                                $('#addRowButton').prop('disabled', true);
                            } else if (!response.allFilled) {
                                $('#periodStatusAlert').text(
                                    'Masih terdapat data yang belum terisi untuk ID karyawan: ' +
                                    response.missingData.join(', ')).show();
                            } else if (response.isCurrentYear) {
                                $('#yearStatusAlert').text(
                                    'Input year matches the current year.').show();
                            } else {
                                $('#alertMessage').hide();
                                $('#addRowButton').prop('disabled', false);
                            }
                        }

                    }
                });
            });

            // Edit button click
            $('.edit-button').on('click', function() {
                var id = $(this).data('id');
                var bulan = $(this).data('bulan');
                $('#addRowModal form').attr('action', '/admin/periode/update/' + id);
                $('#formMethod').val('PUT');
                $('#periode').val(bulan);
                $('#karyawan_id').val(id);
            });

            // Reset form when modal is closed
            $('#addRowModal').on('hidden.bs.modal', function() {
                $('#addRowModal form').attr('action', '{{ route('periode.input') }}');
                $('#formMethod').val('POST');
                $('#periode').val('');
                $('#karyawan_id').val('');
                $('#addRowButton').prop('disabled', false);
                $('#alertMessage').hide();
            });
        });
    </script>
@endsection
