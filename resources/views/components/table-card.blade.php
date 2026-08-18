@props([
    'title' => 'Judul Tabel',
    'icon' => 'fa-solid fa-table',
    'iconColor' => '#64748B',
    'iconBgColor' => '#F1F5F9',
    'header' => null,
    'footer' => null,
])

<div class="table-card">
    <div class="table-card-header">
        <div class="table-card-title">
            <div class="table-card-title-icon" style="background:{{ $iconBgColor }};color:{{ $iconColor }};">
                <i class="{{ $icon }}"></i>
            </div>
            {{ $title }}
        </div>
        @if($header)
            <div>
                {{ $header }}
            </div>
        @endif
    </div>
    <div style="overflow-x:auto;">
        {{ $slot }}
    </div>
    @if($footer)
        <div style="padding:1rem 1.5rem;border-top:1px solid #F1F5F9;">
            {{ $footer }}
        </div>
    @endif
</div>
