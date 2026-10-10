{{-- Summary stat card. Expects $label, $value; optional $sub, $icon, $tone --}}

<div class="card border-0 shadow-sm h-100">

    <div class="card-body d-flex align-items-start gap-3">

        @isset($icon)
            <span class="badge bg-{{ $tone ?? 'primary' }}-subtle text-{{ $tone ?? 'primary' }} rounded-3 p-3 d-print-none">
                <i class="bi {{ $icon }} fs-5"></i>
            </span>
        @endisset

        <div>
            <small class="text-muted d-block">{{ $label }}</small>
            <h5 class="fw-bold mb-0">{{ $value }}</h5>

            @isset($sub)
                <small class="text-muted">{{ $sub }}</small>
            @endisset
        </div>

    </div>

</div>
