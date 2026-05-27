@extends('layouts.app')

@section('title', 'Print Invoice')

@section('styles')
<style>
    @media print {
        body {
            margin: 0;
            padding: 0;
        }
        .btn {
            display: none;
        }
    }
    
    .invoice-container {
        background: white;
        padding: 40px;
        max-width: 800px;
        margin: 20px auto;
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
</style>
@endsection

@section('content')
<div class="text-center mb-4">
    <button onclick="window.print()" class="btn btn-primary">
        <i class="bi bi-printer"></i> Print
    </button>
    <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="invoice-container">
    <div class="invoice-header">
        <div>
            <div class="invoice-title">INVOICE</div>
            <div class="invoice-number">{{ $invoice->invoice_number }}</div>
        </div>
        <div class="text-end">
            <div style="font-size: 1.2em; color: #2c3e50;">Invoice System</div>
            <div style="color: #7f8c8d;">PT. Your Company</div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <h6 style="color: #2c3e50; font-weight: bold;">Bill To:</h6>
            <p style="margin: 0;">
                <strong>{{ $invoice->customer->name }}</strong><br>
                @if($invoice->customer->email)
                    {{ $invoice->customer->email }}<br>
                @endif
                @if($invoice->customer->phone)
                    {{ $invoice->customer->phone }}<br>
                @endif
                @if($invoice->customer->address)
                    {{ $invoice->customer->address }}<br>
                @endif
                @if($invoice->customer->city)
                    {{ $invoice->customer->city }}
                @endif
            </p>
        </div>
        <div class="col-md-6 text-end">
            <p style="margin: 5px 0;">
                <strong>Invoice Date:</strong> {{ $invoice->invoice_date->format('d M Y') }}<br>
                @if($invoice->due_date)
                    <strong>Due Date:</strong> {{ $invoice->due_date->format('d M Y') }}<br>
                @endif
                <strong>Status:</strong> {{ ucfirst($invoice->status) }}
            </p>
        </div>
    </div>

    <table class="table table-bordered" style="margin-bottom: 30px;">
        <thead style="background-color: #ecf0f1;">
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

    <div class="row">
        <div class="col-md-6"></div>
        <div class="col-md-6">
            <table class="table" style="margin-bottom: 0;">
                <tr>
                    <td><strong>Subtotal:</strong></td>
                    <td class="text-end">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td><strong>Tax (10%):</strong></td>
                    <td class="text-end">Rp {{ number_format($invoice->tax, 0, ',', '.') }}</td>
                </tr>
                <tr style="border-top: 2px solid #2c3e50;">
                    <td><h5 style="margin: 0;"><strong>Total:</strong></h5></td>
                    <td class="text-end"><h5 style="margin: 0;">Rp {{ number_format($invoice->total, 0, ',', '.') }}</h5></td>
                </tr>
            </table>
        </div>
    </div>

    @if($invoice->notes)
    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ecf0f1;">
        <h6 style="color: #2c3e50; font-weight: bold;">Notes:</h6>
        <p style="color: #7f8c8d;">{{ $invoice->notes }}</p>
    </div>
    @endif

    <div style="margin-top: 40px; text-align: center; color: #7f8c8d; font-size: 0.9em;">
        <p>Thank you for your business!</p>
        <p>This is a computer-generated invoice and does not require a signature.</p>
    </div>
</div>
@endsection
