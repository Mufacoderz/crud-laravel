<?php

// Import class Migration (kerangka dasar migration Laravel)
use Illuminate\Database\Migrations\Migration;

// Import Blueprint untuk mendefinisikan struktur tabel
use Illuminate\Database\Schema\Blueprint;

// Import Schema facade untuk manipulasi database schema
use Illuminate\Support\Facades\Schema;

// Anonymous class migration (default Laravel versi baru)
return new class extends Migration
{
    /**
     * Method up()
     *
     * Dieksekusi saat menjalankan:
     * php artisan migrate
     *
     * Berfungsi untuk MEMBUAT struktur tabel
     */
    public function up(): void
    {
        // Membuat tabel 'products'
        Schema::create('products', function (Blueprint $table) {

            $table->id();
            $table->string('image');
            $table->string('title');
            $table->text('description');
            $table->bigInteger('price');
            // default(0) artinya jika tidak diisi, otomatis bernilai 0
            $table->integer('stock')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Method down()
     *
     * Dieksekusi saat menjalankan:
     * php artisan migrate:rollback
     *
     * Berfungsi untuk MENGHAPUS tabel
     */
    public function down(): void
    {
        // Menghapus tabel 'products' jika ada
        Schema::dropIfExists('products');
    }
};
