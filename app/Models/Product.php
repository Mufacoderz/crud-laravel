<?php

// Namespace model ini, menandakan file berada di folder App/Models
namespace App\Models;

// Import class Model bawaan Eloquent ORM Laravel
use Illuminate\Database\Eloquent\Model;

// Model Product merepresentasikan tabel "products" di database
class Product extends Model
{
    /**
     * $fillable
     *
     * Digunakan untuk menentukan field apa saja
     * yang BOLEH diisi secara mass-assignment
     * (misalnya lewat create() atau update())
     *
     * @var array
     */
    protected $fillable = [
        'image',        // kolom untuk menyimpan path / nama file gambar produk
        'title',        // judul / nama produk
        'description',  // deskripsi produk
        'price',        // harga produk
        'stock',        // jumlah stok produk
    ];
}
