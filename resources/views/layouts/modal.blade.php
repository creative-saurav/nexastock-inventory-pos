{{-- =====================================================
     AJAX MODAL
===================================================== --}}

<div class="modal fade premium-modal"
     id="ajax-modal"
     tabindex="-1"
     aria-labelledby="modal-title"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered"
         id="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <div class="modal-heading">

                    <div class="modal-heading-icon">
                        <i class="bi bi-plus-lg"></i>
                    </div>

                    <div>
                        <h5 class="modal-title" id="modal-title">
                            {{ get_phrase('Modal title') }}
                        </h5>

                        <p class="modal-subtitle mb-0">
                            {{ get_phrase('Manage your information') }}
                        </p>
                    </div>

                </div>

                <button type="button"
                        class="modal-close-btn"
                        data-bs-dismiss="modal"
                        aria-label="Close">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>

            <div class="modal-body" id="ajax-modal-body">
                {{-- AJAX content will be loaded here --}}
            </div>

        </div>

    </div>
</div>



{{-- =====================================================
     EDIT MODAL
===================================================== --}}

<div class="modal fade premium-modal"
     id="edit-modal"
     tabindex="-1"
     aria-labelledby="edit-modal-title"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered"
         id="edit-modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <div class="modal-heading">

                    <div class="modal-heading-icon edit-icon">
                        <i class="bi bi-pencil-square"></i>
                    </div>

                    <div>

                        <h5 class="modal-title"
                            id="edit-modal-title">

                            {{ get_phrase('Edit') }}

                        </h5>

                        <p class="modal-subtitle mb-0">
                            {{ get_phrase('Update information') }}
                        </p>

                    </div>

                </div>

                <button type="button"
                        class="modal-close-btn"
                        data-bs-dismiss="modal"
                        aria-label="Close">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>

            <div class="modal-body">
                {{-- AJAX content will be loaded here --}}
            </div>

        </div>

    </div>
</div>



{{-- =====================================================
     DELETE MODAL
===================================================== --}}

<div class="modal fade premium-modal"
     id="delete-modal"
     tabindex="-1"
     aria-labelledby="delete-title"
     aria-hidden="true">

    <div class="modal-dialog modal-sm modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-body delete-modal-body">

                <div class="delete-icon">
                    <i class="bi bi-trash3"></i>
                </div>

                <h5 class="delete-title">
                    {{ get_phrase('Are you sure?') }}
                </h5>

                <p class="delete-text">
                    {{ get_phrase("You can't bring it back!") }}
                </p>

                <div class="delete-actions">

                    <button type="button"
                            class="btn btn-light premium-cancel-btn"
                            data-bs-dismiss="modal">

                        {{ get_phrase('Cancel') }}

                    </button>

                    <form action=""
                          method="POST"
                          id="delete-form"
                          class="d-inline">

                        @csrf
                        @method('DELETE')

                        <button type="submit"
                                id="save-btn"
                                class="btn btn-danger premium-delete-btn">

                            <i class="bi bi-trash3 me-1"></i>

                            {{ get_phrase('Delete') }}

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>
</div>



{{-- =====================================================
     CONFIRM MODAL
===================================================== --}}

<div class="modal fade premium-modal"
     id="confirm-modal"
     tabindex="-1"
     aria-labelledby="confirm-title"
     aria-hidden="true">

    <div class="modal-dialog modal-sm modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-body delete-modal-body">

                <div class="confirm-icon">
                    <i class="bi bi-exclamation-lg"></i>
                </div>

                <h5 class="delete-title">
                    {{ get_phrase('Are you sure?') }}
                </h5>

                <p class="delete-text">
                    {{ get_phrase('Once approved, this action cannot be reversed.') }}
                </p>

                <div class="delete-actions">

                    <button type="button"
                            class="btn btn-light premium-cancel-btn"
                            data-bs-dismiss="modal">

                        {{ get_phrase('Cancel') }}

                    </button>

                    <a href=""
                       id="confirm-btn"
                       class="btn btn-primary premium-confirm-btn">

                        {{ get_phrase('Confirm') }}

                    </a>

                </div>

            </div>

        </div>

    </div>
</div>




{{-- =====================================================
     MODAL JAVASCRIPT
===================================================== --}}

<script>

    /*
    |--------------------------------------------------------------------------
    | AJAX Modal
    |--------------------------------------------------------------------------
    */

    function modal(size, url, title) {

        $('#modal-dialog')
            .removeClass()
            .addClass('modal-dialog modal-dialog-centered')
            .addClass(size);

        $('#modal-title').html(title);

        $('#ajax-modal-body').html(`
            <div class="modal-loading">
                <div class="spinner-border text-primary"
                     role="status"
                     aria-hidden="true">
                </div>

                <span>Loading...</span>
            </div>
        `);

        $('#ajax-modal').modal('show');


        if (url) {

            $.ajax({

                url: url,

                method: 'GET',

                success: function(data) {

                    $('#ajax-modal-body').html(data);

                    $(document).trigger('modal:loaded', [$('#ajax-modal-body')]);

                },

                error: function() {

                    $('#ajax-modal-body').html(`
                        <div class="alert alert-danger mb-0">
                            Unable to load content.
                            Please try again.
                        </div>
                    `);

                }

            });

        }

    }



    /*
    |--------------------------------------------------------------------------
    | Edit Modal
    |--------------------------------------------------------------------------
    */

    function edit_modal(size, url, title) {

        $('#edit-modal-dialog')
            .removeClass()
            .addClass('modal-dialog modal-dialog-centered')
            .addClass(size);

        $('#edit-modal-title').html(title);

        $('#edit-modal-dialog .modal-body').html(`
            <div class="modal-loading">
                <div class="spinner-border text-primary"
                     role="status"
                     aria-hidden="true">
                </div>

                <span>Loading...</span>
            </div>
        `);

        $('#edit-modal').modal('show');


        if (url) {

            $.ajax({

                url: url,

                method: 'GET',

                success: function(data) {

                    $('#edit-modal-dialog .modal-body').html(data);

                    $(document).trigger('modal:loaded', [$('#edit-modal-dialog .modal-body')]);

                },

                error: function() {

                    $('#edit-modal-dialog .modal-body').html(`
                        <div class="alert alert-danger mb-0">
                            Unable to load content.
                            Please try again.
                        </div>
                    `);

                }

            });

        }

    }



    /*
    |--------------------------------------------------------------------------
    | Clear AJAX Content On Close
    |--------------------------------------------------------------------------
    |
    | Prevents stale forms (with duplicate IDs) from lingering in the DOM
    | after a modal is closed.
    |
    */

    $(document).on('hidden.bs.modal', '#ajax-modal, #edit-modal', function () {

        $(this).find('.modal-body').empty();

    });



    /*
    |--------------------------------------------------------------------------
    | Delete Modal
    |--------------------------------------------------------------------------
    */

    function delete_modal(url) {

        $('#delete-form').attr('action', url);

        $('#delete-modal').modal('show');

    }



    /*
    |--------------------------------------------------------------------------
    | Confirm Modal
    |--------------------------------------------------------------------------
    */

    function confirm_modal(url) {

        $('#confirm-btn').attr('href', url);

        $('#confirm-modal').modal('show');

    }





</script>