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
                        <a href="">Kriteria Variabel</a>
                    </li>
                </ul>
            </div>
            <div class="col md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Kriteria Variabel Penilaian</h4>
                        <button class="btn btn-primary btn-round ml-auto" data-toggle="modal" data-target="#addRowModal">
                            <i class="fa fa-plus"></i>
                            Tambah
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="add-row" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Nama Variabel</th>
                                    <th>Keterangan Variabel</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kriteria as $k)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $k->kriteria }}</td>
                                    <td>
                                        <button class="btn btn-link btn-success edit-button-bidang" data-toggle="modal" data-target="#addRowModal{{$k->id}}" data-id="{{ $k->id }}" data-name="{{ $k->kriteria }}">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button class="btn btn-link btn-danger" onclick="confirm('Are you sure?') && document.getElementById('delete-form-kriteria-{{ $k->id }}').submit();">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                        <form id="delete-form-kriteria-{{ $k->id }}" action="{{ route('delete-kriteria', $k->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                                {{--  modal--}}
<div class="modal fade" id="addRowModal{{$k->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Kriteria</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="/admin/editKriteria/{{$k->id}}">
                @csrf
                    <div class="form-group">
                        <label for="kriteriaName">Nama Kriteria</label>
                        <input type="text" class="form-control" id="kriteria" value="{{$k->kriteria}}" name="kriteriaName">
                    </div>
                    <input type="hidden" id="id" name="id" value="{{$k->id}}">
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">TUTUP</button>
                <button type="submit" class="btn btn-primary">SIMPAN</button>
            </div>
                </form>
            </div>
        </div>
    </div>
</div>
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

<!-- Modal for Adding/Editing Kriteria -->
<div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header no-bd">
                <h5 class="modal-title">
                    <span class="fw-mediumbold">
                        Tambah Kriteria Variabel Baru
                    </span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="hidden" id="id" name="id">
                            <div class="form-group form-group-default">
                                <label for="kriteria">Nama Kriteria</label>
                                <input type="text" class="form-control" id="kriteria" name="kriteria">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer no-bd">
                    <button type="submit" id="saveButton" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript" >
    $(document).ready(function() {
        // Handle add button click for Kriteria
        $('#addButtonKriteria').on('click', function() {
            $('#id').val(''); // Clear the id in the hidden input field
            $('#kriteria').val(''); // Clear the kriteria input field
        });

        // Handle edit button click for Kriteria
        $('.edit-button-kriteria').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            $('#id').val(id); // Set the id in the hidden input field
            $('#kriteria').val(name); // Set the kriteria input field
            console.log('Kriteria Edit Clicked: ', id, name); // Debugging statement
        });

        // Handle form submission for Kriteria
        $('#editForm').on('submit', function(e) {
            e.preventDefault();
            var id = $('#id').val(); // Get the id from the hidden input field
            var name = $('#kriteria').val(); // Get the kriteria input field
            console.log('Kriteria Form Submitted: ', id, name); // Debugging statement
            $.ajax({
                url: id ? '{{ route('admin.kriteria.update', '') }}/' + id : '{{ route('admin.kriteria.store') }}', // Use different routes for add and edit
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id, // Include the id in the data
                    kriteria: name // Include the kriteria in the data
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                        $('#addRowModal').modal('hide');
                    } else {
                        alert('Failed to update data: ' + response.message);
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
