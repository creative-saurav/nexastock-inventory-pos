<form action="{{ route('categories.store') }}"
      method="POST"
      id="categoryCreateForm">

    @csrf

    <div class="mb-3">
        <label class="form-label">
            Category Name
        </label>

        <input type="text"
               name="name"
               class="form-control"
               placeholder="Enter category name"
               required>
    </div>


    <div class="mb-3">
        <label class="form-label">
            Description
        </label>

        <textarea name="description"
                  class="form-control"
                  rows="4"
                  placeholder="Enter description"></textarea>
    </div>


    <div class="mb-3">
        <label class="form-label">
            Status
        </label>

        <select name="status"
                class="form-select"
                required>

            <option value="1" selected>Active</option>
            <option value="0">Inactive</option>

        </select>
    </div>


    <div class="d-flex justify-content-end gap-2">

        <button type="button"
                class="btn btn-secondary"
                data-bs-dismiss="modal">
            Cancel
        </button>

        <button type="submit"
                class="btn btn-primary">
            Save Category
        </button>

    </div>

</form>