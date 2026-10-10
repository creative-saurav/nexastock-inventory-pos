<form
    action="{{ route('expense_categories.store') }}"
    method="POST"
>


@csrf

<div class="mb-3">

    <label class="form-label">
        Category Name
    </label>

    <input
        type="text"
        name="name"
        class="form-control"
        placeholder="Example: Rent, Salary, Electricity Bill"
        required
    >

</div>


<div class="mb-3">

    <label class="form-label">
        Description
    </label>

    <textarea
        name="description"
        class="form-control"
        rows="3"
        placeholder="Optional"
    ></textarea>

</div>


<div class="mb-3">

    <label class="form-label">
        Status
    </label>

    <select
        name="status"
        class="form-select"
    >

        <option value="1">
            Active
        </option>

        <option value="0">
            Inactive
        </option>

    </select>

</div>


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
        Save Category
    </button>

</div>


</form>
