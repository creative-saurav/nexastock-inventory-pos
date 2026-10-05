<form
    action="{{ route('suppliers.update', $supplier->id) }}"
    method="POST"
    id="supplierEditForm"
>

    @csrf


    {{-- BASIC INFORMATION --}}
    <div class="mb-4">

        <div class="d-flex align-items-center mb-3">

            <div class="me-2">

                <span class="badge bg-primary-subtle text-primary rounded-circle p-2">
                    <i class="bi bi-person-vcard"></i>
                </span>

            </div>

            <div>

                <h6 class="mb-0 fw-semibold">
                    Supplier Information
                </h6>

                <small class="text-muted">
                    Update the supplier information.
                </small>

            </div>

        </div>


        <div class="row g-3">

            {{-- NAME --}}
            <div class="col-md-12">

                <label class="form-label fw-medium">
                    Supplier Name
                    <span class="text-danger">*</span>
                </label>

                <div class="input-group">

                    <span class="input-group-text">
                        <i class="bi bi-person"></i>
                    </span>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        placeholder="e.g. Rahman Traders"
                        value="{{ old('name', $supplier->name) }}"
                        required
                    >

                </div>

            </div>


            {{-- PHONE --}}
            <div class="col-md-6">

                <label class="form-label fw-medium">
                    Phone
                </label>

                <div class="input-group">

                    <span class="input-group-text">
                        <i class="bi bi-telephone"></i>
                    </span>

                    <input
                        type="text"
                        name="phone"
                        class="form-control"
                        placeholder="e.g. 017XXXXXXXX"
                        value="{{ old('phone', $supplier->phone) }}"
                    >

                </div>

            </div>


            {{-- EMAIL --}}
            <div class="col-md-6">

                <label class="form-label fw-medium">
                    Email
                </label>

                <div class="input-group">

                    <span class="input-group-text">
                        <i class="bi bi-envelope"></i>
                    </span>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        placeholder="supplier@example.com"
                        value="{{ old('email', $supplier->email) }}"
                    >

                </div>

            </div>


            {{-- ADDRESS --}}
            <div class="col-md-12">

                <label class="form-label fw-medium">
                    Address
                </label>

                <div class="input-group">

                    <span class="input-group-text">
                        <i class="bi bi-geo-alt"></i>
                    </span>

                    <textarea
                        name="address"
                        class="form-control"
                        rows="3"
                        placeholder="Enter supplier address..."
                    >{{ old('address', $supplier->address) }}</textarea>

                </div>

            </div>


            {{-- STATUS --}}
            <div class="col-md-6">

                <label class="form-label fw-medium">
                    Status
                    <span class="text-danger">*</span>
                </label>

                <select
                    name="status"
                    class="form-select"
                    required
                >

                    <option
                        value="1"
                        {{ old('status', $supplier->status) == 1 ? 'selected' : '' }}
                    >
                        Active
                    </option>

                    <option
                        value="0"
                        {{ old('status', $supplier->status) == 0 ? 'selected' : '' }}
                    >
                        Inactive
                    </option>

                </select>

                <small class="text-muted">
                    Inactive suppliers cannot be selected for new purchases.
                </small>

            </div>

        </div>

    </div>


    {{-- FORM ACTIONS --}}
    <div class="border-top pt-3">

        <div class="d-flex justify-content-between align-items-center">

            <small class="text-muted">

                <i class="bi bi-info-circle me-1"></i>

                Fields marked with
                <span class="text-danger">*</span>
                are required.

            </small>


            <div class="d-flex gap-2">

                <button
                    type="button"
                    class="btn btn-light border"
                    data-bs-dismiss="modal"
                >
                    <i class="bi bi-x-lg me-1"></i>
                    Cancel
                </button>


                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    <i class="bi bi-check-lg me-1"></i>
                    Update Supplier
                </button>

            </div>

        </div>

    </div>

</form>