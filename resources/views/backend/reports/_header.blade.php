{{-- Report page header. Expects $title, $subtitle; optional $from, $to for the period line --}}

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

    <div>
        <h4 class="mb-1 fw-semibold">
            {{ $title }}
        </h4>

        <p class="text-muted mb-0">
            {{ $subtitle }}

            @isset($from)
                <span class="fw-semibold text-dark">
                    &middot;
                    @if($from->isSameDay($to))
                        {{ $from->format('d M Y') }}
                    @else
                        {{ $from->format('d M Y') }} - {{ $to->format('d M Y') }}
                    @endif
                </span>
            @endisset
        </p>
    </div>

    <div class="d-flex gap-2 d-print-none">

        <a href="{{ route('reports') }}" class="btn btn-light border">
            <i class="bi bi-arrow-left me-1"></i>
            All Reports
        </a>

        <button type="button" class="btn btn-primary" onclick="window.print()">
            <i class="bi bi-printer me-1"></i>
            Print
        </button>

    </div>

</div>
