<?php

namespace App\Http\Controllers;

// Mengimpor Model Product agar controller bisa berinteraksi dengan tabel products
use App\Models\Product;

// Digunakan sebagai tipe return untuk method yang mengembalikan view
use Illuminate\View\View;

// Digunakan sebagai tipe return untuk method yang melakukan redirect
use Illuminate\Http\RedirectResponse;

// Digunakan untuk menangani data request dari form (input user)
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Menampilkan daftar produk
     *
     * @return View
     */
    public function index() : View
    {
        // Mengambil semua data produk dari database
        // latest() -> urutkan berdasarkan data terbaru
        // paginate(10) -> tampilkan 10 data per halaman
        $products = Product::latest()->paginate(10);

        // Mengirim data $products ke view products.index
        // compact('products') sama dengan ['products' => $products]
        return view('products.index', compact('products'));
    }

    /**
     * Menampilkan halaman form tambah produk
     *
     * @return View
     */
    public function create(): View
    {
        // Menampilkan view form create product
        return view('products.create');
    }

    /**
     * Menyimpan data produk ke database
     *
     * @param  Request $request -> menangkap semua data input dari form
     * @return RedirectResponse
     */

    // method store ini diguakan utk proses insert ke db
    public function store(Request $request): RedirectResponse
    {
        // Validasi data yang dikirim dari form
        $request->validate([
            'image'         => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'title'         => 'required|min:5',
            'description'   => 'required|min:10',
            'price'         => 'required|numeric',
            'stock'         => 'required|numeric'
        ]);

        // Mengambil file gambar dari request
        $image = $request->file('image');
        // Menyimpan gambar ke folder storage/app/products
        // hashName() membuat nama file unik otomatis
        $image->storeAs('products', $image->hashName());

        // Menyimpan data produk ke database menggunakan Eloquent
        Product::create([
            // Menyimpan nama file gambar ke kolom image
            'image'         => $image->hashName(),
            // Mengambil input title dari form
            'title'         => $request->title,
            'description'   => $request->description,
            'price'         => $request->price,
            'stock'         => $request->stock
        ]);

        // Redirect ke halaman index products
        // with() digunakan untuk mengirim pesan flash ke session
        return redirect()
            ->route('products.index')
            ->with(['success' => 'Data Berhasil Disimpan!']);
    }
}
