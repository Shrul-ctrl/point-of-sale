@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <div class="row">
        <!-- Produk -->
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold text-primary"><i class="bi bi-box-seam"></i> Daftar Produk</h4>
                <div class="input-group w-50 shadow-sm">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" id="searchProduk" class="form-control" placeholder="Cari produk...">
                </div>
            </div>
            <div class="row g-3" id="produkList">
                @foreach($produks as $produk)
                <div class="col-sm-6 col-lg-3 produk-item">
                    <div class="card h-100 border-0 shadow-sm hover-card">
                        @if($produk->foto)
                            <img src="{{ asset('images/'.$produk->foto) }}" class="card-img-top rounded-top" style="height:150px;object-fit:cover;">
                        @else
                            <img src="https://via.placeholder.com/150" class="card-img-top rounded-top">
                        @endif
                        <div class="card-body text-center">
                            <h6 class="fw-bold">{{ $produk->nama_produk }}</h6>
                            <p class="text-success fw-bold mb-2">Rp {{ number_format($produk->harga_jual) }}</p>
                            <button type="button" class="btn btn-primary btn-sm w-100 add-to-cart"
                                data-id="{{ $produk->id }}"
                                data-nama="{{ $produk->nama_produk }}"
                                data-harga="{{ $produk->harga_jual }}">
                                <i class="bi bi-cart-plus"></i> Tambah +
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Keranjang -->
        <div class="col-md-4">
            <div class="bg-light p-3 shadow-sm rounded sticky-top" style="top: 15px;">
                <h4 class="fw-bold text-success mb-3"><i class="bi bi-basket"></i> Transaksi</h4>

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="posForm" action="{{ route('transaksi.store') }}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <label class="fw-bold">Pelanggan</label>
                        <select name="pelanggan_id" class="form-control shadow-sm" required>
                            @foreach($pelanggans as $p)
                                <option value="{{ $p->id }}">{{ $p->nama_lengkap }}</option>
                            @endforeach
                        </select>
                    </div>
                    <table class="table table-sm table-striped" id="cartTable">
                        <thead class="table-secondary">
                            <tr>
                                <th>Produk</th>
                                <th>Qty</th>
                                <th>Sub</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <input type="hidden" name="total" id="total">
                    <div class="mb-2">
                        <label class="fw-bold">Diskon (%)</label>
                        <input type="number" name="diskon" id="diskon" class="form-control shadow-sm" value="0">
                    </div>
                    <div class="bg-dark text-white p-2 text-center rounded mb-2">
                        <h5>Total: Rp <span id="totalDisplay">0</span></h5>
                    </div>
                    <div class="bg-success text-white p-2 text-center rounded mb-3">
                        <h4>Total Bayar: Rp <span id="grandTotalDisplay">0</span></h4>
                        <input type="hidden" name="grand_total" id="grand_total">
                    </div>
                    <button type="submit" class="btn btn-success w-100 btn-lg shadow-sm">
                        <i class="bi bi-check-circle"></i> Simpan Transaksi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.hover-card:hover {
    transform: translateY(-5px);
    transition: 0.3s;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1) !important;
}
</style>

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
                    <td><button type="button" class="btn btn-danger btn-sm remove-item" data-index="${index}">&times;</button></td>
                </tr>
            `;
        });

        // Pastikan total bulat
        total = Math.round(total);
        document.getElementById("total").value = total;
        document.getElementById("totalDisplay").textContent = total.toLocaleString();

        // Diskon otomatis
        let diskonPersen = 0;
        if (total > 1000000) {
            diskonPersen = 15;
        } else if (total > 500000) {
            diskonPersen = 10;
        }

        document.getElementById("diskon").value = diskonPersen;

        // Hitung grand total dan bulatkan ke integer
        let grand = total - Math.floor(total * diskonPersen / 100);
        document.getElementById("grand_total").value = grand;
        document.getElementById("grandTotalDisplay").textContent = grand.toLocaleString();
    }

    // Event untuk hapus item
    document.addEventListener("click", function (e) {
        if (e.target.classList.contains("remove-item")) {
            let index = e.target.dataset.index;
            cart.splice(index, 1);
            renderCart();
        }
    });

    // Event untuk ubah qty
    document.addEventListener("input", function (e) {
        if (e.target.classList.contains("qty-input")) {
            let index = e.target.dataset.index;
            cart[index].qty = parseInt(e.target.value);
            renderCart();
        }
    });

    // Simpan transaksi tanpa refresh halaman
    // document.getElementById("posForm").addEventListener("submit", function (e) {
    //     e.preventDefault(); // cegah refresh

    //     let formData = new FormData(this);

    //     fetch(this.action, {
    //         method: "POST",
    //         body: formData
    //     })
    //     .then(res => res.json())
    //     .then(data => {
    //         alert("Transaksi berhasil disimpan!");
    //         window.location.href = "/riwayat-transaksi"; // redirect ke riwayat transaksi
    //     })
    //     .catch(err => {
    //         console.error(err);
    //         alert("Terjadi kesalahan saat menyimpan transaksi");
    //     });
    // });
</script>


<script>
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

    document.getElementById("searchProduk").addEventListener("input", function(){
        let keyword = this.value.toLowerCase();
        document.querySelectorAll(".produk-item").forEach(item => {
            let namaProduk = item.querySelector("h6").textContent.toLowerCase();
            item.style.display = namaProduk.includes(keyword) ? "" : "none";
        });
    });

    // Cegah submit kalau keranjang kosong
    document.getElementById("posForm").addEventListener("submit", function(e) {
        if (cart.length === 0) {
            alert("Keranjang masih kosong!");
            e.preventDefault();
        }
    });
</script>
@endsection
