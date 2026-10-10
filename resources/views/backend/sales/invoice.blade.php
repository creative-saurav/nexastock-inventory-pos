<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Invoice {{ $sale->invoice_no }} - {{ setting('name') }}</title>

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
    >

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
    >

    <style>
        :root {
            --ink: #1f2937;
            --muted: #6b7280;
            --line: #e5e7eb;
            --soft: #f8fafc;
            --brand: #4f46e5;
            --brand-soft: #eef2ff;
            --success: #059669;
            --success-soft: #ecfdf5;
            --danger: #dc2626;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
            font-size: 13px;
            line-height: 1.5;
            color: var(--ink);
            background: #e5e7eb;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* ---------- Toolbar (screen only) ---------- */
        .toolbar {
            position: sticky;
            top: 0;
            z-index: 10;
            display: flex;
            justify-content: center;
            gap: 10px;
            padding: 12px;
            background: #111827;
        }

        .toolbar button {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            font: inherit;
            font-weight: 600;
            border: 0;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn-print {
            background: var(--brand);
            color: #fff;
        }

        .btn-close {
            background: #374151;
            color: #fff;
        }

        /* ---------- A4 sheet ---------- */
        .invoice {
            width: 210mm;
            min-height: 297mm;
            margin: 24px auto;
            padding: 16mm 15mm 14mm;
            background: #fff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .12);
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .invoice::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: var(--brand);
        }

        /* ---------- Header ---------- */
        .inv-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 24px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--ink);
        }

        .shop {
            display: flex;
            gap: 14px;
        }

        .shop-logo {
            width: 52px;
            height: 52px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: var(--brand);
            color: #fff;
            font-size: 26px;
        }

        .shop-logo.has-logo {
            background: #fff;
            border: 1px solid var(--line);
            overflow: hidden;
        }

        .shop-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .shop-name {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -.3px;
            line-height: 1.2;
        }

        .shop-tagline {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--brand);
            margin-bottom: 6px;
        }

        .shop-contact {
            color: var(--muted);
            font-size: 12px;
        }

        .inv-title {
            text-align: right;
        }

        .inv-title h1 {
            font-size: 32px;
            font-weight: 800;
            letter-spacing: 4px;
            line-height: 1;
            margin-bottom: 10px;
        }

        .inv-meta {
            border-collapse: collapse;
            margin-left: auto;
        }

        .inv-meta td {
            padding: 2px 0 2px 14px;
            font-size: 12px;
        }

        .inv-meta td:first-child {
            color: var(--muted);
            text-align: left;
        }

        .inv-meta td:last-child {
            font-weight: 600;
            text-align: right;
        }

        .status {
            display: inline-block;
            margin-top: 10px;
            padding: 4px 14px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .status-paid {
            background: var(--success-soft);
            color: var(--success);
            border: 1px solid var(--success);
        }

        .status-due {
            background: #fef2f2;
            color: var(--danger);
            border: 1px solid var(--danger);
        }

        /* ---------- Parties ---------- */
        .parties {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin: 22px 0;
        }

        .party {
            padding: 12px 14px;
            background: var(--soft);
            border: 1px solid var(--line);
            border-radius: 8px;
        }

        .label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: var(--muted);
            margin-bottom: 6px;
        }

        .party-name {
            font-size: 14px;
            font-weight: 700;
        }

        .party small {
            display: block;
            color: var(--muted);
            font-size: 12px;
        }

        /* ---------- Items ---------- */
        .items {
            width: 100%;
            border-collapse: collapse;
        }

        .items thead th {
            padding: 10px 12px;
            background: var(--ink);
            color: #fff;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .8px;
            text-align: left;
        }

        .items tbody td {
            padding: 10px 12px;
            border-bottom: 1px solid var(--line);
            vertical-align: top;
        }

        .items tbody tr:nth-child(even) td {
            background: var(--soft);
        }

        .items tbody tr {
            page-break-inside: avoid;
        }

        .items .num {
            text-align: right;
            white-space: nowrap;
        }

        .items .center {
            text-align: center;
        }

        .item-name {
            font-weight: 600;
        }

        .item-sku {
            color: var(--muted);
            font-size: 11px;
        }

        /* ---------- Summary ---------- */
        .summary {
            display: flex;
            justify-content: space-between;
            gap: 30px;
            margin-top: 20px;
            page-break-inside: avoid;
        }

        .summary-left {
            flex: 1;
        }

        .words {
            padding: 12px 14px;
            border-left: 3px solid var(--brand);
            background: var(--brand-soft);
            border-radius: 0 6px 6px 0;
            margin-bottom: 14px;
        }

        .words p {
            font-weight: 600;
            font-style: italic;
        }

        .note {
            color: var(--muted);
            font-size: 11px;
        }

        .totals {
            width: 280px;
            border-collapse: collapse;
        }

        .totals td {
            padding: 6px 0;
        }

        .totals td:last-child {
            text-align: right;
            font-weight: 600;
        }

        .totals .discount td:last-child {
            color: var(--danger);
        }

        .grand {
            display: flex;
            justify-content: space-between;
            margin: 6px 0;
            padding: 10px 12px;
            background: var(--brand);
            color: #fff;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 800;
            text-align: left;
        }

        .totals .paid td:last-child {
            color: var(--success);
        }

        /* ---------- Signatures & footer ---------- */
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: auto;
            padding-top: 60px;
            page-break-inside: avoid;
        }

        .signature {
            width: 200px;
            text-align: center;
            padding-top: 8px;
            border-top: 1px solid var(--ink);
            font-size: 12px;
            font-weight: 600;
        }

        .inv-footer {
            margin-top: 24px;
            padding-top: 14px;
            border-top: 1px dashed var(--line);
            text-align: center;
        }

        .inv-footer h3 {
            font-size: 14px;
            font-weight: 700;
            color: var(--brand);
            margin-bottom: 2px;
        }

        .inv-footer p {
            color: var(--muted);
            font-size: 11px;
        }

        /* ---------- Print ---------- */
        @page {
            size: A4;
            margin: 0;
        }

        @media print {
            body {
                background: #fff;
            }

            .toolbar {
                display: none;
            }

            .invoice {
                width: auto;
                min-height: 297mm;
                margin: 0;
                box-shadow: none;
            }
        }

        @media screen and (max-width: 820px) {
            .invoice {
                width: auto;
                min-height: 0;
                margin: 0;
                padding: 24px 16px;
            }

            .inv-header,
            .summary {
                flex-direction: column;
            }

            .inv-title {
                text-align: left;
            }

            .inv-meta {
                margin-left: 0;
            }

            .parties {
                grid-template-columns: 1fr;
            }

            .totals {
                width: 100%;
            }
        }
    </style>

</head>

<body>

    {{-- TOOLBAR --}}
    <div class="toolbar">

        <button type="button" class="btn-print" onclick="window.print()">
            <i class="bi bi-printer"></i>
            Print
        </button>

        <button type="button" class="btn-close" onclick="window.close()">
            <i class="bi bi-x-lg"></i>
            Close
        </button>

    </div>



    <div class="invoice">

        {{-- HEADER --}}
        <div class="inv-header">

            <div class="shop">

                @if(setting('logo'))
                    <div class="shop-logo has-logo">
                        <img src="{{ asset(setting('logo')) }}" alt="{{ setting('name') }}">
                    </div>
                @else
                    <div class="shop-logo">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 8l-9-5-9 5v8l9 5 9-5V8z"/>
                            <path d="M3 8l9 5 9-5"/>
                            <path d="M12 13v8"/>
                        </svg>
                    </div>
                @endif

                <div>
                    <div class="shop-name">{{ setting('name') }}</div>
                    <div class="shop-tagline">{{ setting('tagline') }}</div>

                    <div class="shop-contact">
                        @if(setting('address'))
                            <div>{{ setting('address') }}</div>
                        @endif
                        @if(setting('phone'))
                            <div>Phone: {{ setting('phone') }}</div>
                        @endif
                        @if(setting('email'))
                            <div>Email: {{ setting('email') }}</div>
                        @endif
                    </div>
                </div>

            </div>


            <div class="inv-title">

                <h1>INVOICE</h1>

                <table class="inv-meta">
                    <tr>
                        <td>Invoice No</td>
                        <td>{{ $sale->invoice_no }}</td>
                    </tr>
                    <tr>
                        <td>Date</td>
                        <td>{{ $sale->created_at->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td>Time</td>
                        <td>{{ $sale->created_at->format('h:i A') }}</td>
                    </tr>
                </table>

                @if($sale->payment_status === 'paid')
                    <span class="status status-paid">Paid</span>
                @else
                    <span class="status status-due">{{ ucfirst($sale->payment_status) }}</span>
                @endif

            </div>

        </div>



        {{-- PARTIES --}}
        <div class="parties">

            <div class="party">
                <div class="label">Bill To</div>
                <div class="party-name">{{ $sale->customer?->name ?? 'Walk-in Customer' }}</div>

                @if($sale->customer)
                    @if($sale->customer->phone)
                        <small>{{ $sale->customer->phone }}</small>
                    @endif
                    <small>{{ $sale->customer->email }}</small>
                @endif
            </div>

            <div class="party">
                <div class="label">Served By</div>
                <div class="party-name">{{ $sale->user?->name ?? 'N/A' }}</div>
                <small>{{ ucfirst($sale->user?->role ?? '') }}</small>
            </div>

            <div class="party">
                <div class="label">Payment</div>
                <div class="party-name">{{ ucwords(str_replace('_', ' ', $sale->payment_method)) }}</div>
                <small>{{ $sale->items->sum('quantity') }} item(s) in {{ $sale->items->count() }} line(s)</small>
            </div>

        </div>



        {{-- ITEMS --}}
        <table class="items">

            <thead>
                <tr>
                    <th style="width: 40px;">#</th>
                    <th>Description</th>
                    <th class="center" style="width: 90px;">Qty</th>
                    <th class="num" style="width: 120px;">Unit Price</th>
                    <th class="num" style="width: 130px;">Amount</th>
                </tr>
            </thead>

            <tbody>
                @foreach($sale->items as $item)
                    <tr>
                        <td>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>

                        <td>
                            <div class="item-name">{{ $item->product?->name ?? 'Product Deleted' }}</div>

                            @if($item->product?->sku)
                                <div class="item-sku">SKU: {{ $item->product->sku }}</div>
                            @endif
                        </td>

                        <td class="center">
                            {{ $item->quantity }} {{ $item->product?->unit?->short_name }}
                        </td>

                        <td class="num">৳{{ number_format($item->price, 2) }}</td>

                        <td class="num"><strong>৳{{ number_format($item->total, 2) }}</strong></td>
                    </tr>
                @endforeach
            </tbody>

        </table>



        {{-- SUMMARY --}}
        <div class="summary">

            <div class="summary-left">

                <div class="words">
                    <div class="label">Amount in Words</div>
                    <p>{{ amount_in_words((float) $sale->grand_total) }}</p>
                </div>

                @if(setting('invoice_note'))
                    <div class="note">
                        <strong>Note:</strong> {{ setting('invoice_note') }}
                    </div>
                @endif

            </div>


            <table class="totals">

                <tr>
                    <td>Subtotal</td>
                    <td>৳{{ number_format($sale->subtotal, 2) }}</td>
                </tr>

                <tr class="discount">
                    <td>Discount</td>
                    <td>- ৳{{ number_format($sale->discount, 2) }}</td>
                </tr>

                <tr>
                    <td>Tax</td>
                    <td>৳{{ number_format($sale->tax, 2) }}</td>
                </tr>

                <tr>
                    <td colspan="2">
                        <div class="grand">
                            <span>Grand Total</span>
                            <span>৳{{ number_format($sale->grand_total, 2) }}</span>
                        </div>
                    </td>
                </tr>

                <tr class="paid">
                    <td>Paid ({{ ucwords(str_replace('_', ' ', $sale->payment_method)) }})</td>
                    <td>৳{{ number_format($sale->paid_amount, 2) }}</td>
                </tr>

                <tr>
                    <td>Change Returned</td>
                    <td>৳{{ number_format($sale->change_amount, 2) }}</td>
                </tr>

            </table>

        </div>



        {{-- SIGNATURES --}}
        <div class="signatures">
            <div class="signature">Customer Signature</div>
            <div class="signature">Authorized Signature</div>
        </div>



        {{-- FOOTER --}}
        <div class="inv-footer">
            @if(setting('invoice_footer'))
                <h3>{{ setting('invoice_footer') }}</h3>
            @endif
            <p>
                This is a computer-generated invoice.
                Printed on {{ now()->format('d M Y, h:i A') }}.
            </p>
        </div>

    </div>



    @if(request('print'))
        <script>
            window.addEventListener('load', function () {
                window.print();
            });
        </script>
    @endif

</body>

</html>
