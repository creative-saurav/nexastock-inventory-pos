<form
    action="{{ route('brands.store') }}"
    method="POST"
    enctype="multipart/form-data"
    id="brandCreateForm"
>


@csrf


{{-- Brand Logo --}}
<div class="mb-3">

    <label class="form-label">
        Brand Logo
    </label>

    <input
        type="file"
        name="logo"
        class="form-control"
        accept="image/*"
    >

    <small class="text-muted">
        JPG, JPEG, PNG, WEBP. Maximum 2MB.
    </small>

</div>


{{-- Brand Name --}}
<div class="mb-3">

    <label class="form-label">
        Brand Name
    </label>

    <input
        type="text"
        name="name"
        class="form-control"
        placeholder="Enter brand name"
        value="{{ old('name') }}"
        required
    >

</div>


{{-- Description --}}
<div class="mb-3">

    <label class="form-label">
        Description
    </label>

    <textarea
        name="description"
        class="form-control"
        rows="4"
        placeholder="Enter brand description"
    >{{ old('description') }}</textarea>

</div>


{{-- Status --}}
<div class="mb-3">

    <label class="form-label">
        Status
    </label>

    <select
        name="status"
        class="form-select"
        required
    >

        <option value="1" selected>
            Active
        </option>

        <option value="0">
            Inactive
        </option>

    </select>

</div>


{{-- Actions --}}
<div class="d-flex justify-content-end gap-2">

    <button
        type="button"
        class="btn btn-secondary"
        data-bs-dismiss="modal"
    >
        Cancel
    </button>

    <button
        type="submit"
        class="btn btn-primary"
    >
        <i class="bi bi-check-lg me-1"></i>
        Save Brand
    </button>

</div>


</form>
