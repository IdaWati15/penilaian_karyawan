@extends('layouts.master')
@section('content')

<div class="main-panel">
    <div class="content">
        <div class="page-inner">
            <div class="page-header">
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="card-title">Data Jabatan</h4>
                            <button class="btn btn-primary btn-round ml-auto" data-toggle="modal" data-target="#addRowModalJabatan" id="addButtonJabatan">
                                <i class="fa fa-plus"></i>
                                Tambah
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="modal fade" id="addRowModalJabatan" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header no-bd">
                                        <h5 class="modal-title">
                                            <span class="fw-mediumbold">
                                                Tambah Jabatan
                                            </span>
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <form id="editFormJabatan">
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <input type="hidden" id="id" name="id">
                                                    <div class="form-group form-group-default">
                                                        <label for="jabatan">Nama Jabatan</label>
                                                        <input type="text" class="form-control" id="jabatan" name="jabatan">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer no-bd">
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="add-row-jabatan" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Jabatan</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($jabatan as $j)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $j->jabatan }}</td>
                                        <td>
                                            <button class="btn btn-link btn-success edit-button-jabatan" data-toggle="modal" data-target="#addRowModalJabatan" data-id="{{ $j->id }}" data-name="{{ $j->jabatan }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button class="btn btn-link btn-danger" onclick="confirm('Are you sure?') && document.getElementById('delete-form-jabatan-{{ $j->id }}').submit();">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                            <form id="delete-form-jabatan-{{ $j->id }}" action="{{ route('delete-jabatan', $j->id) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <br>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="card-title">Data Bidang</h4>
                            <button class="btn btn-primary btn-round ml-auto" data-toggle="modal" data-target="#addRowModalBidang" id="addButtonBidang">
                                <i class="fa fa-plus"></i>
                                Tambah
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="modal fade" id="addRowModalBidang" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header no-bd">
                                        <h5 class="modal-title">
                                            <span class="fw-mediumbold">
                                                Tambah Bidang
                                            </span>
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <form id="editFormBidang">
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <input type="hidden" id="id" name="id">
                                                    <div class="form-group form-group-default">
                                                        <label for="bidang">Nama Bidang</label>
                                                        <input type="text" class="form-control" id="bidang" name="bidang">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer no-bd">
                                            <button type="submit" id="saveButtonBidang" class="btn btn-primary">Simpan</button>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="add-row-bidang" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Bidang</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bidang as $b)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $b->bidang }}</td>
                                        <td>
                                            <button class="btn btn-link btn-success edit-button-bidang" data-toggle="modal" data-target="#addRowModalBidang" data-id="{{ $b->id }}" data-name="{{ $b->bidang }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button class="btn btn-link btn-danger" onclick="confirm('Are you sure?') && document.getElementById('delete-form-bidang-{{ $b->id }}').submit();">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                            <form id="delete-form-bidang-{{ $b->id }}" action="{{ route('delete-bidang', $b->id) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Handle add button click for Jabatan
        $('#addButtonJabatan').on('click', function() {
            $('#id').val(''); // Clear the id in the hidden input field
            $('#jabatan').val(''); // Clear the jabatan input field
        });

        // Handle edit button click for Jabatan
        $('.edit-button-jabatan').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            $('#id').val(id); // Set the id in the hidden input field
            $('#jabatan').val(name);
            console.log('Jabatan Edit Clicked: ', id, name); // Debugging statement
        });

        // Handle form submission for Jabatan
        $('#editFormJabatan').on('submit', function(e) {
            e.preventDefault();
            var id = $('#id').val(); // Get the id from the hidden input field
            var name = $('#jabatan').val();
            console.log('Jabatan Form Submitted: ', id, name); // Debugging statement
            $.ajax({
                url: id ? '{{ route('update-jabatan') }}' : '{{ route('store-jabatan') }}', // Use different routes for add and edit
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id, // Include the id in the data
                    jabatan: name
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                        $('#addRowModalJabatan').modal('hide');
                    } else {
                        alert('Failed to update data');
                    }
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseText);
                }
            });
        });

        // Handle add button click for Bidang
        $('#addButtonBidang').on('click', function() {
            $('#id').val(''); // Clear the id in the hidden input field
            $('#bidang').val(''); // Clear the bidang input field
        });

        // Handle edit button click for Bidang
        $('.edit-button-bidang').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            $('#id').val(id); // Set the id in the hidden input field
            $('#bidang').val(name);
            console.log('Bidang Edit Clicked: ', id, name); // Debugging statement
        });

        // Handle form submission for Bidang
        $('#editFormBidang').on('submit', function(e) {
            e.preventDefault();
            var id = $('#id').val(); // Get the id from the hidden input field
            var name = $('#bidang').val();
            console.log('Bidang Form Submitted: ', id, name); // Debugging statement
            $.ajax({
                url: id ? '{{ route('update-bidang') }}' : '{{ route('store-bidang') }}', // Use different routes for add and edit
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id, // Include the id in the data
                    bidang: name
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                        $('#addRowModalBidang').modal('hide');
                    } else {
                        alert('Failed to update data');
                    }
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseText);
                }
            });
        });
    });
</script>

@endsection