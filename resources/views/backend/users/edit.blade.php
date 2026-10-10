<form action="{{ route('users.update', $user->id) }}"
      method="POST"
      enctype="multipart/form-data">


@csrf

<div class="row g-3">

    {{-- User Image --}}
    <div class="col-12 text-center">
        <div class="mb-3">
            <div class="position-relative d-inline-block">

                @if($user->image)
                    <img src="{{ asset($user->image) }}"
                         id="user_image_preview"
                         class="rounded-circle border"
                         width="100"
                         height="100"
                         style="object-fit: cover;">
                @else
                    <div id="user_image_placeholder"
                         class="rounded-circle border bg-light d-flex align-items-center justify-content-center"
                         style="width:100px;height:100px;">
                        <i class="bi bi-person fs-1 text-secondary"></i>
                    </div>

                    <img src=""
                         id="user_image_preview"
                         class="rounded-circle border d-none"
                         width="100"
                         height="100"
                         style="object-fit: cover;">
                @endif

            </div>
        </div>

        <label class="btn btn-light border">
            <i class="bi bi-camera me-1"></i>
            {{ get_phrase('Choose Image') }}

            <input type="file"
                   name="image"
                   class="d-none"
                   accept="image/*"
                   onchange="previewUserImage(this)">
        </label>

        <div class="form-text">
            {{ get_phrase('JPG, JPEG, PNG or WEBP. Maximum 2MB.') }}
        </div>

        @error('image')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    {{-- Name --}}
    <div class="col-md-6">
        <label class="form-label">
            {{ get_phrase('Name') }} <span class="text-danger">*</span>
        </label>

        <input type="text"
               name="name"
               class="form-control"
               value="{{ old('name', $user->name) }}"
               placeholder="{{ get_phrase('Enter name') }}"
               required>

        @error('name')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    {{-- Email --}}
    <div class="col-md-6">
        <label class="form-label">
            {{ get_phrase('Email') }} <span class="text-danger">*</span>
        </label>

        <input type="email"
               name="email"
               class="form-control"
               value="{{ old('email', $user->email) }}"
               placeholder="{{ get_phrase('Enter email') }}">

        <small class="text-muted">{{ get_phrase('Optional for customers who will not log in.') }}</small>

        @error('email')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    {{-- Phone --}}
    <div class="col-md-6">
        <label class="form-label">
            {{ get_phrase('Phone') }}
        </label>

        <input type="text"
               name="phone"
               class="form-control"
               value="{{ old('phone', $user->phone) }}"
               placeholder="{{ get_phrase('Enter phone number') }}">

        @error('phone')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    {{-- Role --}}
    <div class="col-md-6">
        <label class="form-label">
            {{ get_phrase('Role') }} <span class="text-danger">*</span>
        </label>

        <select name="role" class="form-select" required>
            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                {{ get_phrase('Admin') }}
            </option>

            <option value="manager" {{ old('role', $user->role) == 'manager' ? 'selected' : '' }}>
                {{ get_phrase('Manager') }}
            </option>

            <option value="cashier" {{ old('role', $user->role) == 'cashier' ? 'selected' : '' }}>
                {{ get_phrase('Cashier') }}
            </option>

            <option value="staff" {{ old('role', $user->role) == 'staff' ? 'selected' : '' }}>
                {{ get_phrase('Staff') }}
            </option>

            <option value="customer" {{ old('role', $user->role) == 'customer' ? 'selected' : '' }}>
                {{ get_phrase('Customer') }}
            </option>
        </select>

        @error('role')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    {{-- Status --}}
    <div class="col-md-6">
        <label class="form-label">
            {{ get_phrase('Status') }} <span class="text-danger">*</span>
        </label>

        <select name="status" class="form-select" required>
            <option value="1" {{ old('status', $user->status) == 1 ? 'selected' : '' }}>
                {{ get_phrase('Active') }}
            </option>

            <option value="0" {{ old('status', $user->status) == 0 ? 'selected' : '' }}>
                {{ get_phrase('Inactive') }}
            </option>
        </select>

        @error('status')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

</div>

<div class="d-flex justify-content-end gap-2 mt-4">

    <button type="button"
            class="btn btn-light"
            data-bs-dismiss="modal">
        {{ get_phrase('Cancel') }}
    </button>

    <button type="submit" class="btn btn-primary">
        <i class="bi bi-check-lg me-1"></i>
        {{ get_phrase('Update User') }}
    </button>

</div>


</form>

<script>
function previewUserImage(input) {

    if (input.files && input.files[0]) {

        const reader = new FileReader();

        reader.onload = function(e) {

            const preview = document.getElementById('user_image_preview');
            const placeholder = document.getElementById('user_image_placeholder');

            preview.src = e.target.result;
            preview.classList.remove('d-none');

            if (placeholder) {
                placeholder.classList.add('d-none');
            }
        };

        reader.readAsDataURL(input.files[0]);
    }
}
</script>
