@extends('backend.layouts.admin')

@section('title', get_phrase('Users'))

@section('content')

<div class="container-fluid">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">{{ get_phrase('Users') }}</h4>
            <p class="text-muted mb-0">
                {{ get_phrase('Manage all registered users') }}
            </p>
        </div>

        <div>
            <a href="javascript:void(0)"
               class="btn btn-primary"
               onclick="modal('modal-md', '{{ route('users.create') }}', '{{ get_phrase('Add User') }}')">
                <i class="bi bi-plus-lg me-1"></i>
                {{ get_phrase('Add User') }}
            </a>
        </div>
    </div>


    <!-- Users Card -->
    <div class="card border-0 shadow-sm">
        <div class="p-4 border-bottom">

            <div class="row align-items-center">

                <div class="col-md-6">

                    <h6 class="fw-bold mb-1">
                        All Users
                    </h6>

                    <small class="text-muted">
                        {{ $users->total() }} users found
                    </small>

                </div>


                <div class="col-md-6">

                    <form
                        action="{{ route('users') }}"
                        method="GET"
                    >

                        <div class="input-group">

                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                placeholder="Search users..."
                                value="{{ request('search') }}"
                            >

                            <button
                                class="btn btn-primary"
                                type="submit"
                            >
                                Search
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ get_phrase('User') }}</th>
                            <th>{{ get_phrase('Email') }}</th>
                            <th>{{ get_phrase('Phone') }}</th>
                            <th>{{ get_phrase('Role') }}</th>
                            <th>{{ get_phrase('Status') }}</th>
                            <th class="text-end">{{ get_phrase('Action') }}</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($users as $key => $user)

                            <tr>

                                <td>
                                    {{ $users->firstItem() + $key }}
                                </td>

                                <!-- User -->
                                <td>
                                    <div class="d-flex align-items-center">

                                        @if($user->image)
                                            <img src="{{ asset($user->image) }}"
                                                 alt="{{ $user->name }}"
                                                 class="rounded-circle me-2"
                                                 width="42"
                                                 height="42"
                                                 style="object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-2"
                                                 style="width:42px;height:42px;">
                                                <i class="bi bi-person text-secondary"></i>
                                            </div>
                                        @endif

                                        <div>
                                            <h6 class="mb-0">
                                                {{ $user->name }}
                                            </h6>

                                            <small class="text-muted">
                                                {{ $user->email }}
                                            </small>
                                        </div>

                                    </div>
                                </td>

                                <!-- Email -->
                                <td>
                                    {{ $user->email }}
                                </td>

                                <!-- Phone -->
                                <td>
                                    {{ $user->phone ?? '—' }}
                                </td>

                                <!-- Role -->
                                <td>
                                    @php
                                        $roleClass = match($user->role) {
                                            'admin' => 'bg-danger',
                                            'manager' => 'bg-primary',
                                            'cashier' => 'bg-warning text-dark',
                                            'staff' => 'bg-info text-dark',
                                            'customer' => 'bg-success',
                                            default => 'bg-secondary',
                                        };
                                    @endphp

                                    <span class="badge {{ $roleClass }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>

                                <!-- Status -->
                                <td>

                                    @if($user->status)
                                        <span class="badge bg-success">
                                            {{ get_phrase('Active') }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            {{ get_phrase('Inactive') }}
                                        </span>
                                    @endif

                                </td>

                                <!-- Actions -->
                                <td class="text-end">

                                    <div class="dropdown">

                                        <button class="btn btn-sm btn-light"
                                                type="button"
                                                data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>

                                        <ul class="dropdown-menu dropdown-menu-end">

                                            <!-- Edit -->
                                            <li>
                                                <a href="javascript:void(0)"
                                                   class="dropdown-item"
                                                   onclick="modal('modal-md', '{{ route('users.edit', $user->id) }}', '{{ get_phrase('Edit User') }}')">
                                                    <i class="bi bi-pencil me-2"></i>
                                                    {{ get_phrase('Edit') }}
                                                </a>
                                            </li>

                                            <!-- Delete -->
                                            <li>
                                                <a href="javascript:void(0)"
                                                   class="dropdown-item text-danger"
                                                   onclick="delete_modal('{{ route('users.delete', $user->id) }}')">
                                                    <i class="bi bi-trash me-2"></i>
                                                    {{ get_phrase('Delete') }}
                                                </a>
                                            </li>

                                        </ul>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="text-center py-5">

                                    <div class="text-muted">

                                        <i class="bi bi-people fs-1 d-block mb-2"></i>

                                        <h6>
                                            {{ get_phrase('No users found') }}
                                        </h6>

                                        <p class="mb-0">
                                            {{ get_phrase('Start by adding a new user.') }}
                                        </p>

                                    </div>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            <!-- Pagination -->
            @if($users->hasPages())

                <div class="mt-4">
                    {{ $users->links() }}
                </div>

            @endif

        </div>

    </div>

</div>

@endsection