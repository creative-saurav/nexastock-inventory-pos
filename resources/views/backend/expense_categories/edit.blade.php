<form
    action="{{ route('expense_categories.update', $category->id) }}"
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
        value="{{ $category->name }}"
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
    >{{ $category->description }}</textarea>

</div>


<div class="mb-3">

    <label class="form-label">
        Status
    </label>

    <select
        name="status"
        class="form-select"
    >

        <option value="1" @selected($category->status)>
            Active
        </option>

        <option value="0" @selected(! $category->status)>
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
        Update Category
    </button>

</div>


</form>
