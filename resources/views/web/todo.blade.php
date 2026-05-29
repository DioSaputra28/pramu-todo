@extends('web.layouts.main', ['title' => 'To-Do List - Minimarket Manager'])

@section('body-class', 'bg-background text-on-background font-sans antialiased pb-[104px] pt-[70px] min-h-screen')

@section('content')
    @include('web.layouts.topbar', ['title' => 'To-Do List'])

    <main class="px-4 flex flex-col gap-4 max-w-md mx-auto w-full">
        @if (session('status'))
            <div class="rounded-lg bg-primary-container text-on-primary px-4 py-3 text-sm flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px]">check_circle</span>
                {{ session('status') }}
            </div>
        @endif

        <section class="flex flex-col gap-3">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-bold text-on-background">Barang untuk diambil</h2>
                <span class="bg-primary-container text-on-primary text-[10px] font-semibold px-2 py-[2px] rounded-full">
                    {{ $itemCount }} Item
                </span>
            </div>

            <div class="flex flex-col gap-3">
                @forelse ($pendingItems as $item)
                    <div class="border border-outline-variant rounded-lg p-3 bg-surface-container-lowest flex flex-col gap-3">
                        <div class="flex justify-between items-center gap-3">
                            <div class="flex flex-col gap-1 flex-1 min-w-0">
                                <span class="text-base font-semibold text-on-background truncate">
                                    {{ $item->product?->name ?? 'Produk tidak ditemukan' }}
                                </span>
                                <span class="text-xs text-secondary font-mono">
                                    {{ $item->product?->barcode ?? '—' }}
                                </span>
                            </div>

                            <div class="flex items-center gap-1 shrink-0">
                                <form action="{{ route('restock-items.update', $item) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="action" value="decrement">
                                    <button
                                        class="w-8 h-8 rounded-full border border-outline-variant text-on-background flex items-center justify-center active:bg-surface-container transition-colors disabled:opacity-40"
                                        type="submit"
                                        aria-label="Kurangi jumlah"
                                        @disabled($item->quantity <= 1)
                                    >
                                        <span class="material-symbols-outlined text-[18px]">remove</span>
                                    </button>
                                </form>

                                <span class="w-7 text-center text-sm font-semibold text-on-background tabular-nums">
                                    {{ $item->quantity }}
                                </span>

                                <form action="{{ route('restock-items.update', $item) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="action" value="increment">
                                    <button
                                        class="w-8 h-8 rounded-full border border-outline-variant text-on-background flex items-center justify-center active:bg-surface-container transition-colors"
                                        type="submit"
                                        aria-label="Tambah jumlah"
                                    >
                                        <span class="material-symbols-outlined text-[18px]">add</span>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 justify-end">
                            <form action="{{ route('restock-items.out-of-stock', $item) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button
                                    class="text-tertiary text-xs font-semibold px-3 py-2 rounded border border-tertiary-fixed-dim hover:bg-tertiary-fixed active:scale-95 transition-all whitespace-nowrap flex items-center gap-1"
                                    type="submit"
                                >
                                    <span class="material-symbols-outlined text-[16px]">inventory_2</span>
                                    Stok Kosong
                                </button>
                            </form>
                            <form action="{{ route('restock-items.complete', $item) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button
                                    class="bg-primary-fixed text-primary text-xs font-semibold px-3 py-2 rounded border border-transparent hover:border-primary-container active:bg-primary-container active:text-on-primary transition-colors whitespace-nowrap"
                                    type="submit"
                                >
                                    Selesai Ambil
                                </button>
                            </form>
                            <form
                                action="{{ route('restock-items.destroy', $item) }}"
                                method="POST"
                                onsubmit="return confirm('Hapus barang ini dari to-do?');"
                            >
                                @csrf
                                @method('DELETE')
                                <button
                                    class="w-9 h-9 rounded text-error flex items-center justify-center hover:bg-error-container active:scale-95 transition-all"
                                    type="submit"
                                    aria-label="Hapus barang"
                                >
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="border border-outline-variant rounded-lg p-4 bg-surface-container-lowest text-sm text-secondary">
                        Belum ada barang yang masuk ke to-do.
                    </div>
                @endforelse
            </div>
        </section>

        @if ($outOfStockItems->isNotEmpty())
            @php
                $orderLines = $outOfStockItems
                    ->map(fn ($item) => '- '.($item->product?->name ?? 'Produk tidak ditemukan').' (x'.$item->quantity.')')
                    ->implode("\n");
                $orderText = "Daftar Pesanan Supplier:\n".$orderLines;
            @endphp
            <section class="flex flex-col gap-3">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-bold text-tertiary flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">production_quantity_limits</span>
                        Stok Gudang Habis
                    </h2>
                    <span class="bg-tertiary-container text-on-tertiary text-[10px] font-semibold px-2 py-[2px] rounded-full">
                        {{ $outOfStockItems->count() }} Item
                    </span>
                </div>

                <div class="flex items-center justify-between gap-2 -mt-1">
                    <p class="text-xs text-secondary">Barang ini perlu dipesan ke supplier.</p>
                    <button
                        type="button"
                        class="text-tertiary text-xs font-semibold px-3 py-2 rounded border border-tertiary-fixed-dim hover:bg-tertiary-fixed active:scale-95 transition-all whitespace-nowrap flex items-center gap-1 shrink-0"
                        data-share-order="{{ $orderText }}"
                    >
                        <span class="material-symbols-outlined text-[16px]">share</span>
                        <span data-share-label>Bagikan</span>
                    </button>
                </div>

                <div class="flex flex-col gap-3">
                    @foreach ($outOfStockItems as $item)
                        <div class="border border-tertiary-fixed-dim rounded-lg p-3 bg-tertiary-fixed/30 flex justify-between items-center gap-3">
                            <div class="flex flex-col gap-1 flex-1 min-w-0">
                                <span class="text-base font-semibold text-on-background truncate">
                                    {{ $item->product?->name ?? 'Produk tidak ditemukan' }}
                                </span>
                                <span class="text-xs text-secondary font-mono">
                                    {{ $item->product?->barcode ?? '—' }} · butuh {{ $item->quantity }}
                                </span>
                            </div>

                            <div class="flex items-center gap-1 shrink-0">
                                <form action="{{ route('restock-items.restore', $item) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button
                                        class="text-primary text-xs font-semibold px-3 py-2 rounded border border-primary-container hover:bg-primary-fixed active:scale-95 transition-all whitespace-nowrap flex items-center gap-1"
                                        type="submit"
                                    >
                                        <span class="material-symbols-outlined text-[16px]">undo</span>
                                        Kembalikan
                                    </button>
                                </form>
                                <form
                                    action="{{ route('restock-items.destroy', $item) }}"
                                    method="POST"
                                    onsubmit="return confirm('Hapus barang ini dari to-do?');"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        class="w-9 h-9 rounded text-error flex items-center justify-center hover:bg-error-container active:scale-95 transition-all"
                                        type="submit"
                                        aria-label="Hapus barang"
                                    >
                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($pendingItems->isNotEmpty() || $outOfStockItems->isNotEmpty())
            <form
                action="{{ route('restock-lists.complete') }}"
                method="POST"
                onsubmit="return confirm('Selesaikan sesi ini dan simpan ke riwayat?');"
            >
                @csrf
                <button
                    class="w-full h-12 bg-primary text-on-primary text-sm font-semibold rounded-lg flex items-center justify-center gap-2 active:scale-[0.98] transition-transform"
                    type="submit"
                >
                    <span class="material-symbols-outlined text-[20px]">task_alt</span>
                    Selesaikan Sesi
                </button>
            </form>
        @endif
    </main>

    @include('web.layouts.navbar', ['activeTab' => 'todo'])
@endsection
