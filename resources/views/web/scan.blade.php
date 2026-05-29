@extends('web.layouts.main', ['title' => 'Scan Barang - Minimarket Manager'])

@section('body-class', 'bg-background text-on-background font-sans antialiased pb-[104px] pt-[70px] min-h-screen')

@section('content')
    @include('web.layouts.topbar', ['title' => 'Scan Barang'])

    <main
        class="px-4 flex flex-col gap-4 max-w-md mx-auto w-full"
        data-scan-page
        data-restock-url="{{ route('restock-items.store') }}"
    >
        <section class="flex flex-col gap-3">
            <div
                class="rounded-xl border border-primary-container bg-surface-container-lowest p-4 flex flex-col items-center justify-center min-h-[280px] relative overflow-hidden group"
            >
                <div
                    class="absolute inset-3 border-2 border-transparent border-t-outline border-l-outline w-12 h-12 rounded-tl-lg transition-colors group-hover:border-primary-container"
                ></div>
                <div
                    class="absolute top-3 right-3 border-2 border-transparent border-t-outline border-r-outline w-12 h-12 rounded-tr-lg transition-colors group-hover:border-primary-container"
                ></div>
                <div
                    class="absolute bottom-3 left-3 border-2 border-transparent border-b-outline border-l-outline w-12 h-12 rounded-bl-lg transition-colors group-hover:border-primary-container"
                ></div>
                <div
                    class="absolute bottom-3 right-3 border-2 border-transparent border-b-outline border-r-outline w-12 h-12 rounded-br-lg transition-colors group-hover:border-primary-container"
                ></div>
                <video
                    id="scanVideo"
                    class="absolute inset-0 h-full w-full object-cover hidden"
                    muted
                    playsinline
                ></video>
                <div id="scanPlaceholder" class="flex flex-col items-center">
                    <span class="material-symbols-outlined text-[48px] text-outline mb-2 opacity-50">qr_code_scanner</span>
                    <p class="text-xs text-outline text-center px-4">Arahkan barcode ke kotak</p>
                </div>
            </div>
            <button
                id="cameraToggle"
                class="w-full bg-primary-container text-on-primary text-sm font-semibold py-4 rounded-lg active:scale-[0.98] transition-transform flex justify-center items-center gap-2"
                type="button"
            >
                <span class="material-symbols-outlined text-[20px]">photo_camera</span>
                <span id="cameraLabel">Aktifkan Kamera</span>
            </button>
            <div class="flex items-center justify-between text-xs text-secondary">
                <span id="scanStatus">Tekan Aktifkan Kamera untuk mulai.</span>
                <span class="text-primary font-semibold" id="scanResult">-</span>
            </div>
            <div class="flex items-center justify-between text-xs text-secondary">
                <span>Total item to-do hari ini</span>
                <span class="text-primary font-semibold" id="scanItemCount">{{ $itemCount }}</span>
            </div>
            @if ($latestItem)
                <div class="text-xs text-secondary">
                    Terakhir discan:
                    <span class="text-on-background font-semibold" id="scanLatestItem">
                        {{ $latestItem->product?->name ?? 'Produk tidak ditemukan' }}
                    </span>
                </div>
            @else
                <div class="text-xs text-secondary hidden" id="scanLatestWrapper">
                    Terakhir discan:
                    <span class="text-on-background font-semibold" id="scanLatestItem"></span>
                </div>
            @endif
        </section>

    </main>

    <div
        id="toastContainer"
        class="fixed inset-x-0 bottom-[120px] z-50 flex flex-col items-center gap-2 px-4 pointer-events-none"
        aria-live="polite"
        aria-atomic="true"
    ></div>

    @include('web.layouts.navbar', ['activeTab' => 'scan'])
@endsection
