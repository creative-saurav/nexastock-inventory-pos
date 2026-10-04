<form
    action="{{ route('units.store') }}"
    method="POST"
>


@csrf

<div class="mb-3">

    <label class="form-label">
        Unit Name
    </label>

    <input
        type="text"
        name="name"
        class="form-control"
        placeholder="Enter unit name"
        required
    >

</div>


<div class="mb-3">

    <label class="form-label">
        Short Name
    </label>

    <input
        type="text"
        name="short_name"
        class="form-control"
        placeholder="Example: Kg, Pc, Box"
        required
    >

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
        Save Unit
    </button>

</div>


</form>
