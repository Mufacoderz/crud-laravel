<?php

// Namespace controller ini, menandakan file berada di folder App/Http/Controllers
namespace App\Http\Controllers;

// Import model Product agar bisa berinteraksi dengan tabel products di database
use App\Models\Product;

// Import View agar return type-hinting jelas (opsional tapi best practice)
use Illuminate\View\View;

// Controller ProductController mewarisi base Controller Laravel
class ProductController extends Controller
{
    /**
     * Method index
     *
     * Digunakan untuk menampilkan halaman daftar produk
     *
     * @return View  // Method ini mengembalikan sebuah View
     */
    public function index() : View
    {
        // Mengambil data produk dari database
        // latest()  -> urutkan berdasarkan created_at terbaru
        // paginate(10) -> batasi 10 data per halaman + otomatis handle pagination
        $products = Product::latest()->paginate(10);

        // Mengirim data $products ke view 'products.index'
        // compact('products') sama dengan ['products' => $products]
        return view('products.index', compact('products'));
    }
}
