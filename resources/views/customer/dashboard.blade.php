@extends('backend.layouts.admin')

@section('title', 'My Dashboard')
@section('page-title', 'My Dashboard')

@section('content')

<div class="container-fluid">

    {{-- WELCOME --}}
    <div class="card border-0 shadow-sm mb-4 overflow-hidden">

        <div class="card-body p-4 d-flex flex-wrap align-items-center justify-content-between gap-3">

            <div>
                <h4 class="fw-bold mb-1">
                    Hello, {{ auth()->user()->name }} 👋
                </h4>

                <p class="text-muted mb-0">
                    Thanks for shopping at {{ setting('name') }}. Here are all your purchases in one place.
                </p>
            </div>

            <a href="{{ route('customer.purchases') }}" class="btn btn-primary">
                <i class="bi bi-bag-check me-1"></i>
                View My Purchases
            </a>

        </div>

    </div>



    {{-- KEY NUMBERS --}}
    <div class="row g-3 mb-4">

        @php
            $cards = [
                ['label' => 'Total Purchases', 'value' => $totalPurchases, 'sub' => 'invoice(s)', 'icon' => 'bi-receipt', 'tone' => 'primary'],
                ['label' => 'Total Spent', 'value' => '৳' . number_format($totalSpent, 2), 'sub' => 'across all purchases', 'icon' => 'bi-wallet2', 'tone' => 'success'],
                ['label' => 'You Saved', 'value' => '৳' . number_format($totalSaved, 2), 'sub' => 'in discounts', 'icon' => 'bi-tag', 'tone' => 'warning'],
                ['label' => 'Items Bought', 'value' => $itemsBought, 'sub' => $lastPurchase ? 'Last visit ' . $lastPurchase->created_at->diffForHumans() : 'No visits yet', 'icon' => 'bi-box-seam', 'tone' => 'info'],
            ];
        @endphp

        @foreach($cards as $card)

            <div class="col-md-6 col-xl-3">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body d-flex align-items-start gap-3">

                        <span class="badge bg-{{ $card['tone'] }}-subtle text-{{ $card['tone'] }} rounded-3 p-3">
                            <i class="bi {{ $card['icon'] }} fs-5"></i>
                        </span>

                        <div>
                            <small class="text-muted d-block">{{ $card['label'] }}</small>
                            <h4 class="fw-bold mb-0">{{ $card['value'] }}</h4>
                            <small class="text-muted">{{ $card['sub'] }}</small>
                        </div>

                    </div>

                </div>

            </div>

        @endforeach

    </div>



    {{-- RECENT PURCHASES --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0">Recent Purchases</h6>

            @if($totalPurchases > 5)
                <a href="{{ route('customer.purchases') }}" class="btn btn-sm btn-light border">View All</a>
            @endif
        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Invoice No.</th>
                        <th>Date</th>
                        <th class="text-center">Items</th>
                        <th class="text-end">Amount</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($recentPurchases as $sale)
                        <tr>
                            <td class="ps-4 fw-semibold">{{ $sale->invoice_no }}</td>
                            <td>{{ $sale->created_at->format('d M Y, h:i A') }}</td>
                            <td class="text-center">{{ (int) $sale->items_sum_quantity }}</td>
                            <td class="text-end fw-semibold">৳{{ number_format($sale->grand_total, 2) }}</td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('customer.purchases.show', $sale->id) }}" class="btn btn-sm btn-light border" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('customer.purchases.invoice', ['id' => $sale->id, 'print' => 1]) }}" target="_blank" class="btn btn-sm btn-light border" title="Print Invoice">
                                        <i class="bi bi-printer"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">

                                <div class="mb-2">
                                    <i class="bi bi-bag fs-1 text-muted"></i>
                                </div>

                                <h6 class="fw-semibold">No purchases yet</h6>

                                <p class="text-muted mb-0">
                                    Your invoices will show up here after you shop with us.
                                </p>

                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
