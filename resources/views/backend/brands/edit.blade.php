<form
    action="{{ route('brands.update', $brand->id) }}"
    method="POST"
    enctype="multipart/form-data"
    id="brandEditForm"
>

@csrf


{{-- Current Logo --}}
@if($brand->logo)

    <div class="mb-3">

        <label class="form-label">
            Current Logo
        </label>

        <div class="mb-2">

            <img
                src="{{ asset($brand->logo) }}"
                alt="{{ $brand->name }}"
                style="
                    width: 70px;
                    height: 70px;
                    object-fit: contain;
                    border: 1px solid #e5e7eb;
                    border-radius: 10px;
                    padding: 6px;
                    background: #ffffff;
                "
            >

        </div>

    </div>

@endif


{{-- New Logo --}}
<div class="mb-3">

    <label class="form-label">
        {{ $brand->logo ? 'Change Logo' : 'Brand Logo' }}
    </label>

    <input
        type="file"
        name="logo"
        class="form-control"
        accept="image/*"
    >

    <small class="text-muted">
        Leave empty to keep the current logo.
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
        value="{{ old('name', $brand->name) }}"
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
    >{{ old('description', $brand->description) }}</textarea>

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

        <option
            value="1"
            {{ old('status', $brand->status) == 1 ? 'selected' : '' }}
        >
            Active
        </option>

        <option
            value="0"
            {{ old('status', $brand->status) == 0 ? 'selected' : '' }}
        >
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
        Update Brand
    </button>

</div>


</form>
