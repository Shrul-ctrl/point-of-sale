@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 fw-bold text-primary"><i class="bi bi-receipt"></i> Daftar Transaksi</h2>
    <a href="{{ route('pos.index') }}" class="btn btn-primary mb-3">Tambah</a>

    @if(session('success'))
        <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-striped table-hover mb-0">
            <thead class="table-primary">
                <tr>
                    <th>No</th>
                    <th>Pelanggan</th>
                    <th>Tanggal</th>
                    <th>Produk</th>
                    <th>Total</th>
                    <th>Diskon (%)</th>
                    <th>Total Bayar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksis as $t)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $t->pelanggan->nama_lengkap ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($t->tanggal)->format('d/m/Y H:i') }}</td>
                        <td>
                            <ul class="mb-0">
                                @foreach($t->details as $d)
                                    <li>{{ $d->produk->nama_produk ?? '-' }} x {{ $d->qty }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                        <td>{{ $t->diskon ?? 0 }}%</td>
                        <td class="fw-bold text-success">Rp {{ number_format($t->grand_total, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-3">Belum ada transaksi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

</div>
@endsection
