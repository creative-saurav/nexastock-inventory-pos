@php
    $supplier = $purchase->supplier;

    $grandTotal  = (float) $purchase->grand_total;
    $paidAmount  = (float) $purchase->paid_amount;
    $dueAmount   = (float) $purchase->due_amount;
    $paidPercent = $grandTotal > 0 ? min(100, round(($paidAmount / $grandTotal) * 100)) : 0;
    $totalQty    = $purchase->items->sum(fn ($item) => (float) $item->quantity);

    $statusMap = [
        'received'  => ['label' => 'Received',  'class' => 'success', 'icon' => 'bi-check-circle'],
        'pending'   => ['label' => 'Pending',   'class' => 'warning', 'icon' => 'bi-clock'],
        'cancelled' => ['label' => 'Cancelled', 'class' => 'danger',  'icon' => 'bi-x-circle'],
    ];

    $paymentMap = [
        'paid'    => ['label' => 'Paid',    'class' => 'success', 'icon' => 'bi-check-circle'],
        'partial' => ['label' => 'Partial', 'class' => 'warning', 'icon' => 'bi-hourglass-split'],
        'due'     => ['label' => 'Due',     'class' => 'danger',  'icon' => 'bi-exclamation-circle'],
    ];

    $status  = $statusMap[$purchase->status] ?? $statusMap['pending'];
    $payment = $paymentMap[$purchase->payment_status] ?? $paymentMap['due'];
@endphp


{{-- =========================
    ACTION BAR
========================== --}}
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">

    <div class="d-flex flex-wrap align-items-center gap-2">
        <span class="badge rounded-pill bg-{{ $status['class'] }}-subtle text-{{ $status['class'] }} px-3 py-2">
            <i class="bi {{ $status['icon'] }} me-1"></i>{{ $status['label'] }}
        </span>

        <span class="badge rounded-pill bg-{{ $payment['class'] }}-subtle text-{{ $payment['class'] }} px-3 py-2">
            <i class="bi {{ $payment['icon'] }} me-1"></i>{{ $payment['label'] }}
        </span>
    </div>

    <div class="d-flex gap-2">
        <button type="button" class="btn btn-sm btn-light border" onclick="window.print()">
            <i class="bi bi-printer me-1"></i> Print
        </button>

        <button
            type="button"
            class="btn btn-sm btn-primary"
            onclick="$('#ajax-modal').modal('hide'); edit_modal('modal-xl', '{{ route('purchases.edit', $purchase->id) }}', 'Edit Purchase')"
        >
            <i class="bi bi-pencil-square me-1"></i> Edit
        </button>
    </div>

</div>


{{-- =========================
    PRINTABLE DOCUMENT
========================== --}}
<div id="purchase-print-area" class="border rounded-3 p-3 p-md-4">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 pb-3 mb-3 border-bottom">

        <div>
            <div class="fs-5 fw-bold">
                <i class="bi bi-boxes text-primary me-1"></i> NexaStock
            </div>
            <div class="text-muted small">Purchase Invoice</div>
        </div>

        <div class="text-sm-end">
            <div class="fs-5 fw-bold">{{ $purchase->purchase_no }}</div>
            <div class="text-muted small">
                <i class="bi bi-calendar3 me-1"></i>{{ $purchase->purchase_date->format('d M Y') }}
            </div>
        </div>

    </div>


    {{-- Supplier & Payment --}}
    <div class="row g-3 mb-4">

        <div class="col-md-6">
            <div class="pv-box">
                <div class="pv-label mb-2">Supplier</div>

                <div class="fw-semibold mb-2">{{ $supplier->name }}</div>

                <div class="small text-body-secondary">
                    <div class="mb-1"><i class="bi bi-telephone me-2"></i>{{ $supplier->phone ?: '—' }}</div>
                    <div class="mb-1"><i class="bi bi-envelope me-2"></i>{{ $supplier->email ?: '—' }}</div>
                    <div><i class="bi bi-geo-alt me-2"></i>{{ $supplier->address ?: '—' }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="pv-box">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="pv-label">Payment</span>
                    <span class="small fw-semibold">{{ $paidPercent }}% paid</span>
                </div>

                <div class="progress mb-3" style="height: 6px;" role="progressbar"
                     aria-valuenow="{{ $paidPercent }}" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar bg-{{ $payment['class'] }}" style="width: {{ $paidPercent }}%"></div>
                </div>

                <div class="row text-center g-0">
                    <div class="col">
                        <div class="text-muted small">Total</div>
                        <div class="fw-bold">{{ number_format($grandTotal, 2) }}</div>
                    </div>
                    <div class="col border-start">
                        <div class="text-muted small">Paid</div>
                        <div class="fw-bold text-success">{{ number_format($paidAmount, 2) }}</div>
                    </div>
                    <div class="col border-start">
                        <div class="text-muted small">Due</div>
                        <div class="fw-bold text-danger">{{ number_format($dueAmount, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    {{-- Items --}}
    <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="pv-label">Items</span>
        <span class="small text-muted">
            {{ $purchase->items->count() }} product(s) &middot; Qty {{ number_format($totalQty, 2) }}
        </span>
    </div>

    <div class="table-responsive border rounded-3 mb-4">
        <table class="table pv-table align-middle mb-0">

            <thead>
                <tr>
                    <th class="ps-3" style="width: 48px;">#</th>
                    <th>Product</th>
                    <th class="text-center">Unit</th>
                    <th class="text-end">Qty</th>
                    <th class="text-end">Unit Price</th>
                    <th class="text-end pe-3">Amount</th>
                </tr>
            </thead>

            <tbody>
                @forelse($purchase->items as $index => $item)
                    <tr>
                        <td class="ps-3 text-muted">{{ $index + 1 }}</td>

                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($item->product?->image)
                                    <img src="{{ asset($item->product->image) }}"
                                         alt="{{ $item->product->name }}"
                                         class="pv-thumb rounded-2 border">
                                @else
                                    <div class="pv-thumb bg-light rounded-2 border d-flex align-items-center justify-content-center">
                                        <i class="bi bi-box text-muted"></i>
                                    </div>
                                @endif

                                <div>
                                    <div class="fw-semibold">{{ $item->product->name ?? 'Deleted product' }}</div>
                                    @if($item->product?->sku)
                                        <small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <td class="text-center">
                            <span class="badge bg-light text-dark border">
                                {{ $item->product->unit->short_name ?? 'N/A' }}
                            </span>
                        </td>

                        <td class="text-end">{{ number_format((float) $item->quantity, 2) }}</td>
                        <td class="text-end">{{ number_format((float) $item->purchase_price, 2) }}</td>
                        <td class="text-end pe-3 fw-semibold">{{ number_format((float) $item->subtotal, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-4 d-block mb-1"></i>
                            No purchase items found.
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>


    {{-- Note & Totals --}}
    <div class="row g-4">

        <div class="col-md-7">
            @if($purchase->note)
                <div class="pv-label mb-1">Note</div>
                <p class="small text-body-secondary mb-0" style="white-space: pre-line;">{{ $purchase->note }}</p>
            @endif
        </div>

        <div class="col-md-5">
            <div class="d-flex justify-content-between py-1">
                <span class="text-muted">Subtotal</span>
                <span class="fw-semibold">{{ number_format((float) $purchase->subtotal, 2) }}</span>
            </div>

            <div class="d-flex justify-content-between py-1">
                <span class="text-muted">Discount</span>
                <span class="fw-semibold text-danger">- {{ number_format((float) $purchase->discount, 2) }}</span>
            </div>

            <div class="pv-grand d-flex justify-content-between align-items-center my-2">
                <span class="fw-semibold">Grand Total</span>
                <span class="fs-5 fw-bold">{{ number_format($grandTotal, 2) }}</span>
            </div>

            <div class="d-flex justify-content-between py-1">
                <span class="text-muted">Paid</span>
                <span class="fw-semibold text-success">{{ number_format($paidAmount, 2) }}</span>
            </div>

            <div class="d-flex justify-content-between py-1 border-top">
                <span class="fw-semibold">Balance Due</span>
                <span class="fw-bold text-danger">{{ number_format($dueAmount, 2) }}</span>
            </div>
        </div>

    </div>


    {{-- Footer --}}
    <div class="d-flex flex-wrap justify-content-between gap-2 border-top mt-4 pt-2 small text-muted">
        <span>Created {{ $purchase->created_at->format('d M Y, h:i A') }}</span>
        <span>Printed {{ now()->format('d M Y, h:i A') }}</span>
    </div>

</div>
