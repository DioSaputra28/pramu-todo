@extends('web.layouts.main', ['title' => 'Tambah Produk - Minimarket Manager'])

@section('body-class', 'bg-surface text-on-surface font-sans antialiased min-h-screen pb-[104px]')

@section('content')
    @include('web.layouts.topbar', ['title' => 'Tambah Produk'])

    <main class="pt-20 px-4 flex flex-col gap-4 max-w-md mx-auto">
        <div class="flex flex-col gap-2">
            <label class="text-xs text-secondary">Barcode</label>
            <div class="h-12 bg-surface-container-low border border-outline-variant rounded-lg flex items-center px-3 text-secondary text-sm select-none">
                <span class="material-symbols-outlined mr-2 text-outline">qr_code_scanner</span>
                {{ $barcode !== '' ? $barcode : 'Belum ada barcode' }}
            </div>
            @if ($existingProduct)
                <p class="text-xs text-secondary">
                    Barcode sudah terdaftar untuk produk {{ $existingProduct->name }}.
                </p>
            @endif
        </div>

        <div class="flex flex-col gap-2">
            <label class="text-xs text-on-surface" for="nama_barang">Nama Barang</label>
            <input
                class="h-12 bg-surface border border-outline-variant rounded-lg px-3 text-on-surface text-sm focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary placeholder:text-outline focus:bg-[#EAF1FF] transition-colors"
                id="nama_barang"
                name="nama_barang"
                placeholder="Masukkan nama barang"
                type="text"
            />
        </div>

        <div class="flex flex-col gap-3 mt-4">
            <button
                class="w-full h-12 bg-primary-container text-on-primary text-base font-semibold rounded-lg flex items-center justify-center active:scale-[0.98] transition-transform"
                type="button"
            >
                Simpan
            </button>
            <button
                class="w-full h-12 text-primary text-xs font-semibold rounded-lg flex items-center justify-center hover:bg-surface-container-low active:bg-secondary-container transition-colors"
                type="button"
            >
                Batal
            </button>
        </div>
    </main>

    @include('web.layouts.navbar', ['activeTab' => 'master'])
@endsection
