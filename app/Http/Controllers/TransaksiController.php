<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::with('pelanggan', 'details.produk')
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('transaksi.index', compact('transaksis'));
    }

    public function pos()
    {
        $pelanggans = Pelanggan::all();
        $produks = Produk::all();
        return view('pos.index', compact('pelanggans', 'produks'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'total'        => 'required|numeric',
            'diskon'       => 'nullable|numeric',
            'grand_total'  => 'required|numeric',
            'produk_id'    => 'required|array|min:1',
            'qty'          => 'required|array|min:1',
            'harga'        => 'required|array|min:1',
            'subtotal'     => 'required|array|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ]);
        }

        // Pastikan jumlah array sama
        if (
            count($request->produk_id) !== count($request->qty) ||
            count($request->produk_id) !== count($request->harga) ||
            count($request->produk_id) !== count($request->subtotal)
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Data produk tidak konsisten'
            ]);
        }

        DB::beginTransaction();

        try {
            // Simpan transaksi utama (pastikan integer)
            $transaksi = Transaksi::create([
                'pelanggan_id' => $request->pelanggan_id,
                'tanggal'      => now(),
                'total'        => (int) $request->total,
                'diskon'       => (int) ($request->diskon ?? 0),
                'grand_total'  => (int) $request->grand_total,
            ]);

            // Simpan detail transaksi
            foreach ($request->produk_id as $key => $id) {
                TransaksiDetail::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id'    => $id,
                    'qty'          => (int) $request->qty[$key],
                    'harga'        => (int) $request->harga[$key],
                    'subtotal'     => (int) $request->subtotal[$key],
                ]);
            }

            DB::commit();

            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil disimpan');


        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan transaksi: ' . $e->getMessage()
            ]);
        }
    }
}
