@extends('backend.layouts.admin')

@section('page-title', 'Expense Report')
@section('title', 'Expense Report')


@section('content')

<div class="container-fluid" id="report-print-area">

    @include('backend.reports._header', [
        'title' => 'Expense Report',
        'subtitle' => 'Expenses by category',
    ])


    {{-- FILTERS --}}
    <div class="card border-0 shadow-sm mb-4 d-print-none">

        <div class="card-body">

            <form action="{{ route('reports.expenses') }}" method="GET">

                <div class="row g-2 align-items-end">

                    @include('backend.reports._dates')


                    <div class="col-lg-2 col-md-4">

                        <label class="form-label small text-muted mb-1">Category</label>

                        <select name="category_id" class="form-select">

                            <option value="">All Categories</option>

                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach

                        </select>

                    </div>


                    <div class="col-lg-2 col-md-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-funnel me-1"></i>
                            Filter
                        </button>
                    </div>

                </div>

            </form>

        </div>

    </div>



    {{-- SUMMARY --}}
    <div class="row g-3 mb-4 report-stats">

        <div class="col-md-4">
            @include('backend.reports._stat', ['label' => 'Total Expenses', 'value' => '৳' . number_format($total, 2), 'icon' => 'bi-wallet2', 'tone' => 'danger'])
        </div>

        <div class="col-md-4">
            @include('backend.reports._stat', ['label' => 'Entries', 'value' => number_format($count), 'icon' => 'bi-journal-text', 'tone' => 'primary'])
        </div>

        <div class="col-md-4">
            @include('backend.reports._stat', [
                'label' => 'Biggest Category',
                'value' => $byCategory->first()?->name ?? '—',
                'sub' => $byCategory->first() ? '৳' . number_format($byCategory->first()->total, 2) : null,
                'icon' => 'bi-pie-chart',
                'tone' => 'warning',
            ])
        </div>

    </div>



    <div class="row g-3">

        {{-- BY CATEGORY --}}
        <div class="col-xl-5">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">By Category</h6>
                </div>

                <div class="card-body">

                    @forelse($byCategory as $row)

                        @php
                            $share = $total > 0 ? ($row->total / $total) * 100 : 0;
                        @endphp

                        <div class="mb-3">

                            <div class="d-flex justify-content-between mb-1">
                                <span class="fw-medium">
                                    {{ $row->name }}
                                    <small class="text-muted">({{ $row->count }})</small>
                                </span>
                                <span class="fw-semibold">৳{{ number_format($row->total, 2) }}</span>
                            </div>

                            <div class="progress" style="height: 6px;" role="progressbar" aria-label="{{ $row->name }} share" aria-valuenow="{{ round($share) }}" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar bg-danger" style="width: {{ $share }}%"></div>
                            </div>

                            <small class="text-muted">{{ number_format($share, 1) }}% of total</small>

                        </div>

                    @empty

                        <p class="text-muted text-center mb-0 py-3">No expenses in this period.</p>

                    @endforelse

                </div>

            </div>

        </div>


        {{-- EXPENSE LIST --}}
        <div class="col-xl-7">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">Expenses</h6>
                </div>

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Date</th>
                                <th>Expense No.</th>
                                <th>Category</th>
                                <th>Note</th>
                                <th class="text-end pe-4">Amount</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($expenses as $expense)
                                <tr>
                                    <td class="ps-4">{{ $expense->expense_date->format('d M Y') }}</td>
                                    <td class="fw-semibold">{{ $expense->expense_no }}</td>
                                    <td>{{ $expense->category?->name ?? 'N/A' }}</td>
                                    <td class="text-muted small">{{ $expense->note ? \Illuminate\Support\Str::limit($expense->note, 40) : '—' }}</td>
                                    <td class="text-end pe-4 fw-semibold">৳{{ number_format($expense->amount, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No expenses in this period.</td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>

                </div>

                @if($expenses->hasPages())
                    <div class="card-footer bg-white border-0 py-3 d-print-none">
                        {{ $expenses->links() }}
                    </div>
                @endif

            </div>

        </div>

    </div>

</div>

@endsection
