<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 20px;
        }
        .invoice-title {
            font-size: 2em;
            font-weight: bold;
            color: #2c3e50;
        }
        .invoice-number {
            font-size: 0.9em;
            color: #7f8c8d;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th {
            background-color: #ecf0f1;
            padding: 10px;
            text-align: left;
            border: 1px solid #bdc3c7;
        }
        td {
            padding: 10px;
            border: 1px solid #bdc3c7;
        }
        .text-end {
            text-align: right;
        }
        .summary-table {
            width: 50%;
            margin-left: 50%;
            border: none;
            margin-top: 20px;
        }
        .summary-table td {
            border: none;
            padding: 5px 0;
        }
        .total-row {
            border-top: 2px solid #2c3e50;
            font-weight: bold;
            font-size: 1.1em;
        }
        .notes {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ecf0f1;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <div>
                <div class="invoice-title">INVOICE</div>
                <div class="invoice-number">{{ $invoice->invoice_number }}</div>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 1.2em; color: #2c3e50;">Invoice System</div>
                <div style="color: #7f8c8d;">PT. Your Company</div>
            </div>
        </div>

        <table style="margin-top: 20px; margin-bottom: 20px;">
            <tr>
                <td style="width: 50%; border: none;">
                    <strong>Bill To:</strong><br>
                    {{ $invoice->customer->name }}<br>
                    @if($invoice->customer->email)
                        {{ $invoice->customer->email }}<br>
                    @endif
                    @if($invoice->customer->phone)
                        {{ $invoice->customer->phone }}<br>
                    @endif
                    @if($invoice->customer->address)
                        {{ $invoice->customer->address }}<br>
                    @endif
                </td>
                <td style="width: 50%; border: none; text-align: right;">
                    <strong>Invoice Date:</strong> {{ $invoice->invoice_date->format('d M Y') }}<br>
                    @if($invoice->due_date)
                        <strong>Due Date:</strong> {{ $invoice->due_date->format('d M Y') }}<br>
                    @endif
                    <strong>Status:</strong> {{ ucfirst($invoice->status) }}
                </td>
            </tr>
        </table>

        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-end">Qty</th>
                    <th class="text-end">Unit Price</th>
                    <th class="text-end">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                <tr>
                    <td>
                        <strong>{{ $item->item_name }}</strong><br>
                        <small style="color: #7f8c8d;">{{ $item->description }}</small>
                    </td>
                    <td class="text-end">{{ $item->quantity }}</td>
                    <td class="text-end">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <table class="summary-table">
            <tr>
                <td><strong>Subtotal:</strong></td>
                <td class="text-end">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Tax (10%):</strong></td>
                <td class="text-end">Rp {{ number_format($invoice->tax, 0, ',', '.') }}</td>
            </tr>
            <tr class="total-row">
                <td><strong>Total:</strong></td>
                <td class="text-end">Rp {{ number_format($invoice->total, 0, ',', '.') }}</td>
            </tr>
        </table>

        @if($invoice->notes)
        <div class="notes">
            <strong>Notes:</strong><br>
            {{ $invoice->notes }}
        </div>
        @endif

        <div style="margin-top: 40px; text-align: center; color: #7f8c8d; font-size: 0.9em;">
            <p>Thank you for your business!</p>
        </div>
    </div>
</body>
</html>
