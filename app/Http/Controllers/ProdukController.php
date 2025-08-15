<?php
namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = Produk::latest()->paginate(5);
        return view('produk.index', compact('produks'));
    }

    public function create()
    {
        return view('produk.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'nama_produk' => 'required',
        'kode_produk' => 'required',
        'harga_jual' => 'required|numeric',
        'stok' => 'required|numeric',
        'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
    ]);

    $fotoName = null;
    if ($request->hasFile('foto')) {
        $fotoName = time() . '.' . $request->foto->extension();
        $request->foto->move(public_path('images'), $fotoName);
    }

    Produk::create([
        'nama_produk' => $request->nama_produk,
        'kode_produk' => $request->kode_produk,
        'harga_jual' => $request->harga_jual,
        'stok' => $request->stok,
        'foto' => $fotoName
    ]);

    return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan');
}


    public function edit(Produk $produk)
    {
        return view('produk.edit', compact('produk'));
    }

    public function update(Request $request, Produk $produk)
    {
        $request->validate([
            'nama_produk' => 'required',
            'kode_produk' => 'required|unique:produks,kode_produk,'.$produk->id,
            'harga_jual' => 'required|numeric',
            'stok' => 'required|integer',
        ]);

        $produk->update($request->all());
        return redirect()->route('produk.index')->with('success', 'Produk berhasil diupdate!');
    }

    public function destroy(Produk $produk)
    {
        $produk->delete();
        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus!');
    }
}
