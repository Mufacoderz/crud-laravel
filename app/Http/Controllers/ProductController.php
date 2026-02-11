<?php

namespace App\Http\Controllers;
// Namespace = lokasi class ini di struktur folder Laravel

// import model Product (agar bisa akses tabel products)
use App\Models\Product;

// import tipe return View (biar method bisa ditandai return View)
use Illuminate\View\View;

// import Request (untuk ambil data dari form)
use Illuminate\Http\Request;

// import RedirectResponse (untuk redirect setelah proses)
use Illuminate\Http\RedirectResponse;

// import Storage (untuk hapus file gambar dari folder storage)
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * METHOD: index()
     * Menampilkan semua data produk
     */
    public function index() : View
    {
        // ambil semua data product, urut dari terbaru
        // paginate(10) artinya tampilkan 10 data per halaman
        $products = Product::latest()->paginate(10);

        // kirim data $products ke view products.index
        return view('products.index', compact('products'));
    }

    /**
     * METHOD: create()
     * Menampilkan halaman form tambah produk
     */
    public function create(): View
    {
        // hanya menampilkan halaman form
        return view('products.create');
    }

    /**
     * METHOD: store()
     * Menyimpan data baru ke database
     */
    public function store(Request $request): RedirectResponse
    {
        // validasi input form
        $request->validate([
            // wajib ada file gambar, harus jpg/png, max 2MB
            'image'         => 'required|image|mimes:jpeg,jpg,png|max:2048',

            // title minimal 5 karakter
            'title'         => 'required|min:5',

            // description minimal 10 karakter
            'description'   => 'required|min:10',

            // price & stock harus angka
            'price'         => 'required|numeric',
            'stock'         => 'required|numeric'
        ]);

        // ambil file gambar dari form
        $image = $request->file('image');

        // simpan gambar ke folder storage/app/public/products
        // hashName() = nama file random agar tidak bentrok
        $image->storeAs('public/products', $image->hashName());

        // simpan data ke database
        Product::create([
            'image'         => $image->hashName(),
            'title'         => $request->title,
            'description'   => $request->description,
            'price'         => $request->price,
            'stock'         => $request->stock
        ]);

        // redirect ke halaman index + kirim pesan sukses
        return redirect()->route('products.index')
                        ->with(['success' => 'Data Berhasil Disimpan!']);
    }

    /**
     * METHOD: show()
     * Menampilkan detail 1 produk
     */
    public function show(string $id): View
    {
        // cari product berdasarkan id
        // kalau tidak ketemu → otomatis error 404
        $product = Product::findOrFail($id);

        // kirim ke halaman show
        return view('products.show', compact('product'));
    }

    /**
     * METHOD: edit()
     * Menampilkan form edit produk
     */
    public function edit(string $id): View
    {
        // ambil data product berdasarkan id
        $product = Product::findOrFail($id);

        // kirim ke halaman edit
        return view('products.edit', compact('product'));
    }

    /**
     * METHOD: update()
     * Mengupdate data produk
     */
    public function update(Request $request, $id): RedirectResponse
    {
        // validasi (gambar tidak wajib saat update)
        $request->validate([
            'image'         => 'image|mimes:jpeg,jpg,png|max:2048',
            'title'         => 'required|min:5',
            'description'   => 'required|min:10',
            'price'         => 'required|numeric',
            'stock'         => 'required|numeric'
        ]);

        // ambil data product dari database
        $product = Product::findOrFail($id);

        // cek apakah user upload gambar baru
        if ($request->hasFile('image')) {

            // hapus gambar lama dari storage
            // CATATAN: ini path-nya harus sama dengan saat upload
            Storage::delete('products/'.$product->image);

            // ambil file baru
            $image = $request->file('image');

            // simpan file baru
            $image->storeAs('products', $image->hashName());

            // update semua data termasuk gambar
            $product->update([
                'image'         => $image->hashName(),
                'title'         => $request->title,
                'description'   => $request->description,
                'price'         => $request->price,
                'stock'         => $request->stock
            ]);

        } else {

            // jika tidak upload gambar
            // update data selain gambar
            $product->update([
                'title'         => $request->title,
                'description'   => $request->description,
                'price'         => $request->price,
                'stock'         => $request->stock
            ]);
        }

        // kembali ke halaman index
        return redirect()->route('products.index')
                        ->with(['success' => 'Data Berhasil Diubah!']);
    }

    /**
     * METHOD: destroy()
     * Menghapus produk
     */
    public function destroy($id): RedirectResponse
    {
        // ambil data product
        $product = Product::findOrFail($id);

        // hapus gambar dari storage
        Storage::delete('products/'.$product->image);

        // hapus data dari database
        $product->delete();

        // redirect kembali ke index
        return redirect()->route('products.index')
                        ->with(['success' => 'Data Berhasil Dihapus!']);
    }
}
