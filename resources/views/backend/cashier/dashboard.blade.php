@extends('backend.layouts.admin')

@section('title', 'Cashier Dashboard')
@section('page-title', 'Cashier Dashboard')

@section('content')

<div class="container-fluid">

    {{-- PAGE HEADER --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <h4 class="fw-semibold mb-1">
                Welcome, {{ auth()->user()->name }}
            </h4>

            <p class="text-muted mb-0">
                Your sales summary for {{ now()->format('l, d F Y') }}.
            </p>
        </div>

        <a href="{{ route('pos') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-cart-plus me-1"></i>
            Start New Sale
        </a>

    </div>



    {{-- KEY NUMBERS --}}
    <div class="row g-3 mb-4">

        @php
            $cards = [
                ['label' => 'My Sales Today', 'value' => '৳' . number_format($todaySales, 2), 'icon' => 'bi-cash-coin', 'tone' => 'primary'],
                ['label' => 'Invoices Today', 'value' => $todayCount, 'icon' => 'bi-receipt', 'tone' => 'success'],
                ['label' => 'My Sales This Month', 'value' => '৳' . number_format($monthSales, 2), 'icon' => 'bi-graph-up-arrow', 'tone' => 'info'],
                ['label' => 'Invoices This Month', 'value' => $monthCount, 'icon' => 'bi-journal-text', 'tone' => 'warning'],
            ];
        @endphp

        @foreach($cards as $card)

            <div class="col-md-6 col-xl-3">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body d-flex align-items-center gap-3">

                        <span class="badge bg-{{ $card['tone'] }}-subtle text-{{ $card['tone'] }} rounded-3 p-3">
                            <i class="bi {{ $card['icon'] }} fs-5"></i>
                        </span>

                        <div>
                            <small class="text-muted d-block">{{ $card['label'] }}</small>
                            <h4 class="fw-bold mb-0">{{ $card['value'] }}</h4>
                        </div>

                    </div>

                </div>

            </div>

        @endforeach

    </div>



    {{-- TODAY'S COLLECTION --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">
            <h6 class="fw-bold mb-0">Today's Collection</h6>
            <small class="text-muted">Use this to match your cash drawer at the end of the day</small>
        </div>

        <div class="card-body">

            <div class="row g-3">

                @foreach(['cash' => ['Cash in Drawer', 'bi-cash-stack', 'success'], 'card' => ['Card', 'bi-credit-card', 'primary'], 'mobile_banking' => ['Mobile Banking', 'bi-phone', 'info']] as $method => [$label, $icon, $tone])

                    <div class="col-md-4">

                        <div class="d-flex align-items-center gap-3 p-3 rounded-3 border h-100">

                            <span class="badge bg-{{ $tone }}-subtle text-{{ $tone }} rounded-3 p-2">
                                <i class="bi {{ $icon }} fs-5"></i>
                            </span>

                            <div>
                                <small class="text-muted d-block">{{ $label }}</small>
                                <span class="fw-bold fs-5">৳{{ number_format($todayByMethod[$method] ?? 0, 2) }}</span>
                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    </div>



    <div class="row g-3">

        <div class="col-xl-7">
            @include('backend.dashboard._sales_chart', ['chartTitle' => 'My Sales - Last 14 Days'])
        </div>


        <div class="col-xl-5">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">My Recent Sales</h6>
                </div>

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Invoice</th>
                                <th>Customer</th>
                                <th class="text-end pe-4">Amount</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($recentSales as $sale)
                                <tr>
                                    <td class="ps-4">
                                        <a href="{{ route('sales.show', $sale->id) }}" class="fw-semibold text-decoration-none">
                                            {{ $sale->invoice_no }}
                                        </a>
                                        <div>
                                            <small class="text-muted">{{ $sale->created_at->diffForHumans() }}</small>
                                        </div>
                                    </td>
                                    <td>{{ $sale->customer?->name ?? 'Walk-in Customer' }}</td>
                                    <td class="text-end pe-4 fw-semibold">৳{{ number_format($sale->grand_total, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">
                                        You haven't made any sales yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
