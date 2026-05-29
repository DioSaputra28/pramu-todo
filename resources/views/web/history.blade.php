@extends('web.layouts.main', ['title' => 'Riwayat Restock - Minimarket Manager'])

@section('body-class', 'bg-background text-on-background font-sans antialiased pb-[104px] pt-[70px] min-h-screen')

@section('content')
    @include('web.layouts.topbar', ['title' => 'Riwayat Restock'])

    <main class="px-4 flex flex-col gap-4 max-w-md mx-auto w-full">
        @if (session('status'))
            <div class="rounded-lg bg-primary-container text-on-primary px-4 py-3 text-sm flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px]">check_circle</span>
                {{ session('status') }}
            </div>
        @endif

        <section class="flex flex-col gap-3">
            <h2 class="text-lg font-bold text-on-background">Sesi yang sudah selesai</h2>

            <div class="flex flex-col gap-3">
                @forelse ($restockLists as $list)
                    <a
                        href="{{ route('history.show', $list) }}"
                        class="border border-outline-variant rounded-lg p-3 bg-surface-container-lowest flex justify-between items-center gap-3 active:bg-surface-container transition-colors"
                    >
                        <div class="flex flex-col gap-1">
                            <span class="text-base font-semibold text-on-background">
                                Sesi #{{ $list->id }}
                            </span>
                            <span class="text-xs text-secondary">
                                {{ $list->updated_at->translatedFormat('d M Y, H:i') }}
                            </span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex flex-col items-end">
                                <span class="text-sm font-semibold text-primary">{{ $list->items_count }} jenis</span>
                                <span class="text-xs text-secondary">{{ $list->items_sum_quantity ?? 0 }} unit</span>
                            </div>
                            <span class="material-symbols-outlined text-outline">chevron_right</span>
                        </div>
                    </a>
                @empty
                    <div class="border border-outline-variant rounded-lg p-4 bg-surface-container-lowest text-sm text-secondary">
                        Belum ada sesi restock yang selesai.
                    </div>
                @endforelse
            </div>
        </section>
    </main>

    @include('web.layouts.navbar', ['activeTab' => 'history'])
@endsection
