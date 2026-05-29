@extends('web.layouts.main', ['title' => 'Detail Sesi - Minimarket Manager'])

@section('body-class', 'bg-background text-on-background font-sans antialiased pb-[104px] pt-[70px] min-h-screen')

@section('content')
    @include('web.layouts.topbar', ['title' => 'Sesi #' . $restockList->id])

    <main class="px-4 flex flex-col gap-4 max-w-md mx-auto w-full">
        <section class="flex flex-col gap-3">
            <div class="flex justify-between items-center">
                <div class="flex flex-col">
                    <h2 class="text-lg font-bold text-on-background">Detail Sesi</h2>
                    <span class="text-xs text-secondary">
                        {{ $restockList->updated_at->translatedFormat('d M Y, H:i') }}
                    </span>
                </div>
                <span class="bg-primary-container text-on-primary text-[10px] font-semibold px-2 py-[2px] rounded-full">
                    {{ $restockList->items->count() }} Item
                </span>
            </div>

            <div class="flex flex-col gap-3">
                @forelse ($restockList->items as $item)
                    <div class="border border-outline-variant rounded-lg p-3 bg-surface-container-lowest flex justify-between items-center gap-3">
                        <div class="flex flex-col gap-1 flex-1 min-w-0">
                            <span class="text-base font-semibold text-on-background truncate">
                                {{ $item->product?->name ?? 'Produk tidak ditemukan' }}
                            </span>
                            <span class="text-xs text-secondary font-mono">
                                {{ $item->product?->barcode ?? '—' }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <span class="text-sm font-semibold text-on-background tabular-nums">x{{ $item->quantity }}</span>
                            @if ($item->status === 'done')
                                <span class="material-symbols-outlined text-primary text-[20px]" title="Selesai diambil">check_circle</span>
                            @else
                                <span class="material-symbols-outlined text-outline text-[20px]" title="Belum diambil">radio_button_unchecked</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="border border-outline-variant rounded-lg p-4 bg-surface-container-lowest text-sm text-secondary">
                        Sesi ini tidak memiliki barang.
                    </div>
                @endforelse
            </div>

            <a
                href="{{ route('history.index') }}"
                class="w-full h-12 text-primary text-sm font-semibold rounded-lg flex items-center justify-center hover:bg-surface-container-low active:bg-secondary-container transition-colors"
            >
                Kembali ke Riwayat
            </a>
        </section>
    </main>

    @include('web.layouts.navbar', ['activeTab' => 'history'])
@endsection
