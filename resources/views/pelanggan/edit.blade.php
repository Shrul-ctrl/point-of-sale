@extends('layouts.app')

@section('content')
    <h2 class="mb-4">Edit Pelanggan</h2>
    <form action="{{ route('pelanggan.update', $pelanggan->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="form-control" value="{{ $pelanggan->nama_lengkap }}" required>
        </div>
        <div class="mb-3">
            <label>No HP</label>
            <input type="text" name="nomor_hp" class="form-control" value="{{ $pelanggan->nomor_hp }}" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ $pelanggan->email }}">
        </div>
        <button class="btn btn-success">Update</button>
        <a href="{{ route('pelanggan.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
@endsection
