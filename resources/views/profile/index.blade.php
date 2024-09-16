<!-- resources/views/profile/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <!-- Profile Sidebar -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <img src="{{ asset(Auth::user()->profile_image ?? 'images\no-profile-picture.png') }}?{{ time() }}" class="rounded-circle" alt="Profile Image" width="180">
                    <h4 class="mt-2">{{ Auth::user()->name }}</h4>
                    <p>{{ Auth::user()->email }}</p>
                </div>
            </div>
        </div>

        <!-- Profile Content -->
        <div class="col-md-9">
            <!-- Ubah Data Form -->
            <div class="card mb-3">
                <div class="card-header">Ubah Data</div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}">
                        </div>
						<br>
                        <div class="form-group">
                            <input type="file" class="form-control" id="profile_image" name="profile_image">
                        </div>
						<br>
                        <button type="submit" class="btn btn-primary">Ubah</button>
                    </form>
                </div>
            </div>

            <!-- Ubah Password Form -->
            <div class="card">
                <div class="card-header">Ubah Password</div>
                <div class="card-body">
                    <form action="{{ route('profile.password.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="current_password">Password Lama</label>
                            <input type="password" class="form-control" id="current_password" name="current_password">
                        </div>
                        <div class="form-group">
                            <label for="new_password">Password Baru</label>
                            <input type="password" class="form-control" id="new_password" name="new_password">
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                        </div>
						<br>
                        <button type="submit" class="btn btn-primary">Ubah</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection