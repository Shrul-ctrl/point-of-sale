@extends('layouts.app')

@section('content')
<div class="row">
    <!-- Kiri: Daftar Produk -->
    <div class="col-md-8">
        <h4>Pilih Produk</h4>
        <div class="row">
            @foreach($produks as $produk)
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5>{{ $produk->nama }}</h5>
                        <p>Rp {{ number_format($produk->harga) }}</p>
                        <button type="button" class="btn btn-primary btn-sm add-to-cart"
                            data-id="{{ $produk->id }}"
                            data-nama="{{ $produk->nama }}"
                            data-harga="{{ $produk->harga }}">
                            Tambah
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Kanan: Keranjang -->
    <div class="col-md-4">
        <h4>Keranjang</h4>
        <form action="{{ route('transaksi.store') }}" method="POST">
            @csrf
            <div class="mb-2">
                <label>Pelanggan</label>
                <select name="pelanggan_id" class="form-control" required>
                    @foreach($pelanggans as $p)
                        <option value="{{ $p->id }}">{{ $p->nama }}</option>
                    @endforeach
                </select>
            </div>
            <table class="table table-sm" id="cartTable">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <input type="hidden" name="total" id="total">
            <div class="mb-2">
                <label>Diskon (%)</label>
                <input type="number" name="diskon" id="diskon" class="form-control" value="0">
            </div>
            <div class="mb-2">
                <label>Grand Total</label>
                <input type="number" name="grand_total" id="grand_total" class="form-control" readonly>
            </div>
            <button type="submit" class="btn btn-success w-100">Simpan Transaksi</button>
        </form>
    </div>
</div>

<script>
    let cart = [];

    function renderCart() {
        let tbody = document.querySelector("#cartTable tbody");
        tbody.innerHTML = "";
        let total = 0;
        cart.forEach((item, index) => {
            let subtotal = item.harga * item.qty;
            total += subtotal;
            tbody.innerHTML += `
                <tr>
                    <td>
                        ${item.nama}
                        <input type="hidden" name="produk_id[]" value="${item.id}">
                        <input type="hidden" name="harga[]" value="${item.harga}">
                        <input type="hidden" name="subtotal[]" value="${subtotal}">
                    </td>
                    <td>
                        <input type="number" name="qty[]" class="form-control form-control-sm qty-input" data-index="${index}" value="${item.qty}" min="1">
                    </td>
                    <td>Rp ${subtotal.toLocaleString()}</td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-item" data-index="${index}">X</button></td>
                </tr>
            `;
        });
        document.getElementById("total").value = total;
        let diskon = parseFloat(document.getElementById("diskon").value) || 0;
        document.getElementById("grand_total").value = total - (total * diskon / 100);
    }

    document.querySelectorAll(".add-to-cart").forEach(btn => {
        btn.addEventListener("click", () => {
            let id = btn.dataset.id;
            let nama = btn.dataset.nama;
            let harga = parseFloat(btn.dataset.harga);
            let existing = cart.find(p => p.id == id);
            if (existing) {
                existing.qty++;
            } else {
                cart.push({ id, nama, harga, qty: 1 });
            }
            renderCart();
        });
    });

    document.getElementById("cartTable").addEventListener("input", e => {
        if (e.target.classList.contains("qty-input")) {
            let index = e.target.dataset.index;
            cart[index].qty = parseInt(e.target.value);
            renderCart();
        }
    });

    document.getElementById("cartTable").addEventListener("click", e => {
        if (e.target.classList.contains("remove-item")) {
            let index = e.target.dataset.index;
            cart.splice(index, 1);
            renderCart();
        }
    });

    document.getElementById("diskon").addEventListener("input", renderCart);
</script>
@endsection
