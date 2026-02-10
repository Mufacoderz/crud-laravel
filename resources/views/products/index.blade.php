<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Products - Ujang store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background: lightgray">

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div>
                    <h3 class="text-center my-4">Tutorial Laravel 12 untuk Pemula</h3>
                    <h5 class="text-center">
                        <a href="https://santrikoding.com">Ujang Store</a>
                    </h5>
                    <hr>
                </div>

                <div class="card border-0 shadow-sm rounded">
                    <div class="card-body">

                        {{--
                            route('products.create')
                            → route resource: products.create
                            → memanggil method create() di ProductController
                            → biasanya menampilkan form tambah produk
                        --}}
                        <a href="{{ route('products.create') }}"
                            class="btn btn-md btn-success mb-3">
                            ADD PRODUCT
                        </a>

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">IMAGE</th>
                                    <th scope="col">TITLE</th>
                                    <th scope="col">PRICE</th>
                                    <th scope="col">STOCK</th>
                                    <th scope="col" style="width: 20%">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>

                                {{--
                                    @forelse
                                    → looping data $products dari controller
                                    → $products berasal dari:
                                    Product::latest()->paginate(10)
                                --}}
                                @forelse ($products as $product)
                                    <tr>
                                        <td class="text-center">

                                            {{--
                                                asset('/storage/products/'.$product->image)
                                                → generate URL ke public/storage/products
                                                → butuh perintah:
                                                php artisan storage:link
                                            --}}
                                            <img src="{{ asset('/storage/products/'.$product->image) }}"
                                                    class="rounded"
                                                    style="width: 150px">
                                        </td>

                                        {{-- Menampilkan kolom title dari model Product --}}
                                        <td>{{ $product->title }}</td>

                                        {{--
                                            Format harga ke rupiah
                                            number_format(angka, desimal, pemisah_desimal, pemisah_ribuan)
                                        --}}
                                        <td>
                                            {{ "Rp " . number_format($product->price, 2, ',', '.') }}
                                        </td>

                                        {{-- Menampilkan stok --}}
                                        <td>{{ $product->stock }}</td>

                                        <td class="text-center">

                                            {{--
                                                Form delete produk
                                                HTML tidak support DELETE
                                                Laravel pakai method spoofing
                                            --}}
                                            <form
                                                onsubmit="return confirm('Apakah Anda Yakin ?');"
                                                action="{{ route('products.destroy', $product->id) }}"
                                                method="POST"
                                            >

                                                {{--
                                                    route('products.show', $product->id)
                                                    → memanggil method show(Product $product)
                                                --}}
                                                <a href="{{ route('products.show', $product->id) }}"
                                                    class="btn btn-sm btn-dark">
                                                    SHOW
                                                </a>

                                                {{--
                                                    route('products.edit', $product->id)
                                                    → memanggil method edit(Product $product)
                                                --}}
                                                <a href="{{ route('products.edit', $product->id) }}"
                                                    class="btn btn-sm btn-primary">
                                                    EDIT
                                                </a>

                                                {{--
                                                    @csrf
                                                    → token keamanan Laravel
                                                    → wajib untuk semua form
                                                --}}
                                                @csrf

                                                {{--
                                                    @method('DELETE')
                                                    → mengubah POST menjadi DELETE
                                                    → memanggil method destroy()
                                                --}}
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-sm btn-danger">
                                                    HAPUS
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                {{--
                                    Jika data $products kosong
                                --}}
                                @empty
                                    <div class="alert alert-danger">
                                        Data Products belum ada.
                                    </div>
                                @endforelse

                            </tbody>
                        </table>

                        {{--
                            $products->links()
                            → pagination otomatis Laravel
                            → muncul karena controller pakai paginate()
                        --}}
                        {{ $products->links() }}

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        {{--
            session('success') / session('error')
            → flash message dari controller
            → biasanya dikirim setelah store/update/delete
        --}}
        @if(session('success'))
            Swal.fire({
                icon: "success",
                title: "BERHASIL",
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2000
            });
        @elseif(session('error'))
            Swal.fire({
                icon: "error",
                title: "GAGAL!",
                text: "{{ session('error') }}",
                showConfirmButton: false,
                timer: 2000
            });
        @endif
    </script>

</body>
</html>
