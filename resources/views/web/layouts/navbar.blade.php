@php
    $activeTab = $activeTab ?? 'scan';
@endphp

<nav class="fixed bottom-0 left-0 w-full z-50 h-16 pb-6 border-t border-outline-variant bg-surface flex items-center justify-around">
    <a
        class="@class([
            'flex flex-col items-center justify-center w-1/4 pt-1 transition-opacity active:opacity-80',
            'text-primary font-semibold' => $activeTab === 'scan',
            'text-secondary' => $activeTab !== 'scan',
        ])"
        href="{{ route('scan') }}"
    >
        <span
            class="material-symbols-outlined text-[24px]"
            @if ($activeTab === 'scan') style="font-variation-settings: 'FILL' 1;" @endif
        >
            qr_code_scanner
        </span>
        <span class="text-xs mt-1">Scan</span>
    </a>
    <a
        class="@class([
            'flex flex-col items-center justify-center w-1/4 pt-1 transition-opacity active:opacity-80',
            'text-primary font-semibold' => $activeTab === 'todo',
            'text-secondary' => $activeTab !== 'todo',
        ])"
        href="{{ route('todo') }}"
    >
        <span
            class="material-symbols-outlined text-[24px]"
            @if ($activeTab === 'todo') style="font-variation-settings: 'FILL' 1;" @endif
        >
            checklist
        </span>
        <span class="text-xs mt-1">To-Do</span>
    </a>
    <a
        class="@class([
            'flex flex-col items-center justify-center w-1/4 pt-1 transition-opacity active:opacity-80',
            'text-primary font-semibold' => $activeTab === 'history',
            'text-secondary' => $activeTab !== 'history',
        ])"
        href="{{ route('history.index') }}"
    >
        <span
            class="material-symbols-outlined text-[24px]"
            @if ($activeTab === 'history') style="font-variation-settings: 'FILL' 1;" @endif
        >
            history
        </span>
        <span class="text-xs mt-1">Riwayat</span>
    </a>
    <a
        class="@class([
            'flex flex-col items-center justify-center w-1/4 pt-1 transition-opacity active:opacity-80',
            'text-primary font-semibold' => $activeTab === 'master',
            'text-secondary' => $activeTab !== 'master',
        ])"
        href="{{ route('master.index') }}"
    >
        <span
            class="material-symbols-outlined text-[24px]"
            @if ($activeTab === 'master') style="font-variation-settings: 'FILL' 1;" @endif
        >
            inventory_2
        </span>
        <span class="text-xs mt-1">Master</span>
    </a>
</nav>
