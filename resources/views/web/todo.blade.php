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
                @forelse ($items as $item)
                    <div class="border border-outline-variant rounded-lg p-3 bg-surface-container-lowest flex justify-between items-center gap-3">
                        <div class="flex flex-col gap-1 flex-1">
                            <span class="text-base font-semibold text-on-background">
                                {{ $item->product?->name ?? 'Produk tidak ditemukan' }}
                            </span>
                            <span class="text-xs text-secondary font-mono">
                                {{ $item->product?->barcode ?? '—' }}
                            </span>
                        </div>
                        <form
                            action="{{ route('restock-items.complete', $item) }}"
                            method="POST"
                        >
                            @csrf
                            @method('PATCH')
                            <button
                                class="bg-primary-fixed text-primary text-xs font-semibold px-3 py-2 rounded border border-transparent hover:border-primary-container active:bg-primary-container active:text-on-primary transition-colors whitespace-nowrap"
                                type="submit"
                            >
                                Selesai Ambil
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="border border-outline-variant rounded-lg p-4 bg-surface-container-lowest text-sm text-secondary">
                        Belum ada barang yang masuk ke to-do.
                    </div>
                @endforelse
            </div>
        </section>
    </main>

    @include('web.layouts.navbar', ['activeTab' => 'todo'])
@endsection
