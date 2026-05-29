@extends('web.layouts.main', ['title' => 'Master Produk - Minimarket Manager'])

@section('body-class', 'bg-background text-on-background font-sans antialiased min-h-screen')

@section('content')
    @include('web.layouts.topbar', ['title' => 'Minimarket Manager'])

    <main class="pt-[72px] pb-[104px]">
        <div class="px-4 mb-4">
            <h2 class="text-xl font-bold text-on-background mb-3">Master Produk</h2>
            <div class="relative flex items-center h-12 border border-outline-variant rounded-lg bg-surface-container-lowest focus-within:bg-secondary-container transition-colors">
                <span class="material-symbols-outlined text-outline ml-3 mr-2 pointer-events-none">search</span>
                <input
                    class="w-full h-full bg-transparent border-none focus:ring-0 text-on-background text-sm placeholder:text-outline pr-3"
                    placeholder="Cari nama atau scan barcode..."
                    name="q"
                    type="text"
                    value="{{ $query }}"
                />
            </div>
        </div>

        <div class="px-4 flex flex-col gap-3">
            @forelse ($products as $product)
                <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-3 flex items-center justify-between active:bg-surface-container transition-colors">
                    <div class="flex flex-col">
                        <span class="text-base font-semibold text-on-background mb-1">{{ $product->name }}</span>
                        <span class="text-xs text-secondary flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">barcode</span>
                            {{ $product->barcode }}
                        </span>
                    </div>
                    <button class="text-primary p-2 active:scale-95 transition-transform" type="button" aria-label="Edit Product">
                        <span class="material-symbols-outlined">edit</span>
                    </button>
                </div>
            @empty
                <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-4 text-sm text-secondary">
                    Belum ada produk yang terdaftar.
                </div>
            @endforelse
        </div>
    </main>

    <a
        href="{{ route('master.create') }}"
        class="fixed bottom-[104px] right-4 w-14 h-14 bg-primary text-on-primary rounded-full flex items-center justify-center active:scale-95 transition-transform z-40"
        aria-label="Tambah Produk"
    >
        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">add</span>
    </a>

    @include('web.layouts.navbar', ['activeTab' => 'master'])
@endsection
