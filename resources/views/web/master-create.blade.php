@extends('web.layouts.main', ['title' => 'Tambah Produk - Minimarket Manager'])

@section('body-class', 'bg-surface text-on-surface font-sans antialiased min-h-screen pb-[104px]')

@section('content')
    @include('web.layouts.topbar', ['title' => 'Tambah Produk'])

    <main class="pt-20 px-4 flex flex-col gap-4 max-w-md mx-auto">
        @if ($existingProduct)
            <div class="rounded-lg bg-error-container text-on-error-container px-4 py-3 text-sm">
                Barcode <span class="font-semibold">{{ $existingProduct->barcode }}</span> sudah terdaftar
                untuk produk <span class="font-semibold">{{ $existingProduct->name }}</span>.
            </div>
        @endif

        <form action="{{ route('master.store') }}" method="POST" class="flex flex-col gap-4">
            @csrf

            <div class="flex flex-col gap-2">
                <label class="text-xs text-secondary" for="barcode">Barcode</label>
                @if ($barcode !== '')
                    <div class="h-12 bg-surface-container-low border border-outline-variant rounded-lg flex items-center px-3 text-secondary text-sm select-none">
                        <span class="material-symbols-outlined mr-2 text-outline">qr_code_scanner</span>
                        {{ $barcode }}
                    </div>
                    <input type="hidden" name="barcode" value="{{ $barcode }}">
                @else
                    <input
                        class="h-12 bg-surface border border-outline-variant rounded-lg px-3 text-on-surface text-sm focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary placeholder:text-outline focus:bg-[#EAF1FF] transition-colors"
                        id="barcode"
                        name="barcode"
                        placeholder="Masukkan atau scan barcode"
                        type="text"
                        value="{{ old('barcode') }}"
                        inputmode="numeric"
                    />
                @endif
                @error('barcode')
                    <p class="text-xs text-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-xs text-on-surface" for="name">Nama Barang</label>
                <input
                    class="h-12 bg-surface border border-outline-variant rounded-lg px-3 text-on-surface text-sm focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary placeholder:text-outline focus:bg-[#EAF1FF] transition-colors"
                    id="name"
                    name="name"
                    placeholder="Masukkan nama barang"
                    type="text"
                    value="{{ old('name') }}"
                    required
                    autofocus
                />
                @error('name')
                    <p class="text-xs text-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col gap-3 mt-4">
                <button
                    class="w-full h-12 bg-primary-container text-on-primary text-base font-semibold rounded-lg flex items-center justify-center active:scale-[0.98] transition-transform disabled:opacity-50 disabled:active:scale-100"
                    type="submit"
                    @if ($existingProduct) disabled @endif
                >
                    Simpan
                </button>
                <a
                    href="{{ route('master.index') }}"
                    class="w-full h-12 text-primary text-xs font-semibold rounded-lg flex items-center justify-center hover:bg-surface-container-low active:bg-secondary-container transition-colors"
                >
                    Batal
                </a>
            </div>
        </form>
    </main>

    @include('web.layouts.navbar', ['activeTab' => 'master'])
@endsection
