{{-- Daily sales bar chart. Expects $chart = ['labels' => [], 'values' => [], 'counts' => []] --}}

<div class="card border-0 shadow-sm h-100">

    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">

        <div>
            <h6 class="fw-bold mb-0">{{ $chartTitle ?? 'Sales - Last 14 Days' }}</h6>
            <small class="text-muted">
                Total ৳{{ number_format(array_sum($chart['values']), 2) }}
                from {{ array_sum($chart['counts']) }} sale(s)
            </small>
        </div>

    </div>

    <div class="card-body">

        <div style="position: relative; height: 280px;">
            <canvas
                id="salesChart"
                role="img"
                aria-label="Bar chart of daily sales totals for the last {{ count($chart['labels']) }} days"
            ></canvas>
        </div>

    </div>

</div>


@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<script>
    "use strict";

    (function () {

        const chartData = @json($chart);

        const taka = (value) => '৳' + Number(value).toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });

        new Chart(document.getElementById('salesChart'), {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Sales',
                    data: chartData.values,
                    backgroundColor: '#6366f1',
                    hoverBackgroundColor: '#4f46e5',
                    borderRadius: { topLeft: 4, topRight: 4 },
                    borderSkipped: 'bottom',
                    maxBarThickness: 28,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#111827',
                        padding: 10,
                        displayColors: false,
                        callbacks: {
                            label: (ctx) => taka(ctx.parsed.y),
                            afterLabel: (ctx) => chartData.counts[ctx.dataIndex] + ' sale(s)',
                        },
                    },
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#6b7280', font: { size: 11 } },
                    },
                    y: {
                        beginAtZero: true,
                        border: { display: false },
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            color: '#6b7280',
                            font: { size: 11 },
                            maxTicksLimit: 5,
                            callback: (value) => '৳' + Number(value).toLocaleString('en-US'),
                        },
                    },
                },
            },
        });

    })();
</script>

@endpush
