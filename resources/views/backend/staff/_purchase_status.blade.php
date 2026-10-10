@if($status === 'received')
    <span class="badge bg-success-subtle text-success">
        <i class="bi bi-check-circle me-1"></i>Received
    </span>
@elseif($status === 'pending')
    <span class="badge bg-warning-subtle text-warning">
        <i class="bi bi-clock me-1"></i>Pending
    </span>
@else
    <span class="badge bg-danger-subtle text-danger">
        <i class="bi bi-x-circle me-1"></i>Cancelled
    </span>
@endif
