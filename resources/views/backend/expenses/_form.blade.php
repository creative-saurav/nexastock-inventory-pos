@if($categories->isEmpty())

    <div class="alert alert-warning">
        No active expense category found.
        <a href="{{ route('expense_categories') }}">Create a category</a> first.
    </div>

@endif


<div class="row">

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Category <span class="text-danger">*</span>
        </label>

        <select
            name="expense_category_id"
            class="form-select"
            required
        >

            <option value="">
                Select category
            </option>

            @foreach($categories as $category)

                <option
                    value="{{ $category->id }}"
                    @selected(($expense->expense_category_id ?? null) == $category->id)
                >
                    {{ $category->name }}
                </option>

            @endforeach

        </select>

    </div>


    <div class="col-md-6 mb-3">

        <label class="form-label">
            Amount (৳) <span class="text-danger">*</span>
        </label>

        <input
            type="number"
            name="amount"
            class="form-control"
            step="0.01"
            min="0.01"
            placeholder="0.00"
            value="{{ $expense->amount ?? '' }}"
            required
        >

    </div>


    <div class="col-md-6 mb-3">

        <label class="form-label">
            Expense Date <span class="text-danger">*</span>
        </label>

        <input
            type="date"
            name="expense_date"
            class="form-control"
            max="{{ now()->toDateString() }}"
            value="{{ isset($expense) ? $expense->expense_date->toDateString() : now()->toDateString() }}"
            required
        >

    </div>


    <div class="col-md-6 mb-3">

        <label class="form-label">
            Payment Method <span class="text-danger">*</span>
        </label>

        <select
            name="payment_method"
            class="form-select"
            required
        >

            @foreach($paymentMethods as $value => $label)

                <option
                    value="{{ $value }}"
                    @selected(($expense->payment_method ?? 'cash') === $value)
                >
                    {{ $label }}
                </option>

            @endforeach

        </select>

    </div>


    <div class="col-12 mb-3">

        <label class="form-label">
            Reference
        </label>

        <input
            type="text"
            name="reference"
            class="form-control"
            placeholder="Bill no., voucher no., transaction ID (optional)"
            value="{{ $expense->reference ?? '' }}"
        >

    </div>


    <div class="col-12 mb-3">

        <label class="form-label">
            Note
        </label>

        <textarea
            name="note"
            class="form-control"
            rows="3"
            placeholder="What was this expense for? (optional)"
        >{{ $expense->note ?? '' }}</textarea>

    </div>

</div>
