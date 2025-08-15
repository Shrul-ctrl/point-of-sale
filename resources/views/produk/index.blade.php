@extends('layouts.app')

@section('content')
    <h2 class="mb-4">Daftar Produk</h2>
    <a href="{{ route('produk.create') }}" class="btn btn-primary mb-3">Tambah Produk</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-hover bg-white">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Foto</th> {{-- Tambah kolom foto --}}
                <th>Nama</th>
                <th>Kode</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($produks as $p)
                <tr>
                      <td>{{ $loop->iteration }}</td>
                    <td>
                        @if($p->foto)
                            <img src="{{ asset('images/'.$p->foto) }}" alt="Foto Produk" width="50" height="50" style="object-fit:cover;">
                        @else
                            <img src="https://via.placeholder.com/50" alt="No Image" width="50" height="50">
                        @endif
                    </td>
                    <td>{{ $p->nama_produk }}</td>
                    <td>{{ $p->kode_produk }}</td>
                    <td>Rp {{ number_format($p->harga_jual) }}</td>
                    <td>{{ $p->stok }}</td>
                    <td>
                        <a href="{{ route('produk.edit', $p->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('produk.destroy', $p->id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center">Tidak ada produk</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
