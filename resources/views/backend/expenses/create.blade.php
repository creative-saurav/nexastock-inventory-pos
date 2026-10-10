<form
    action="{{ route('expenses.store') }}"
    method="POST"
>

@csrf

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
        Save Expense
    </button>

</div>


</form>
