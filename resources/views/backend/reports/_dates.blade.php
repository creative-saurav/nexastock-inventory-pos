{{-- Date range inputs for report filter forms. Picking a preset clears the custom dates and submits. --}}

<div class="col-lg-2 col-md-4">

    <label class="form-label small text-muted mb-1">Period</label>

    <select
        name="preset"
        class="form-select"
        onchange="if (this.value) { this.form.from.value = ''; this.form.to.value = ''; } this.form.submit();"
    >

        <option value="">Custom range</option>

        @foreach(\App\Http\Controllers\ReportController::PRESETS as $value => $label)

            <option
                value="{{ $value }}"
                @selected(request('preset', request()->hasAny(['from', 'to']) ? '' : 'this_month') === $value)
            >
                {{ $label }}
            </option>

        @endforeach

    </select>

</div>


<div class="col-lg-2 col-md-4">

    <label class="form-label small text-muted mb-1">From</label>

    <input
        type="date"
        name="from"
        class="form-control"
        value="{{ $from->toDateString() }}"
        onchange="this.form.preset.value = '';"
    >

</div>


<div class="col-lg-2 col-md-4">

    <label class="form-label small text-muted mb-1">To</label>

    <input
        type="date"
        name="to"
        class="form-control"
        value="{{ $to->toDateString() }}"
        onchange="this.form.preset.value = '';"
    >

</div>
