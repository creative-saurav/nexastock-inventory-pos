<form
    action="{{ route('expenses.update', $expense->id) }}"
    method="POST"
>

@csrf

<div class="mb-3 text-muted small">
    Expense No: <strong class="text-dark">{{ $expense->expense_no }}</strong>
</div>

@include('backend.expenses._form')


<div class="d-flex justify-content-end gap-2">

    <button
        type="button"
        class="btn btn-light"
        data-bs-dismiss="modal"
    >
        Cancel
    </button>

    <button
        type="submit"
        class="btn btn-primary"
    >
        Update Expense
    </button>

</div>


</form>
