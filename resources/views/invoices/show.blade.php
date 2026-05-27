@extends('layouts.app')

@section('title', 'View Invoice')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h1><i class="bi bi-file-earmark-text"></i> Invoice {{ $invoice->invoice_number }}</h1>
        <p class="text-muted">{{ $invoice->invoice_date->format('d M Y') }}</p>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('invoices.print', $invoice) }}" class="btn btn-secondary">
            <i class="bi bi-printer"></i> Print
        </a>
        <a href="{{ route('invoices.export-pdf', $invoice) }}" class="btn btn-danger">
            <i class="bi bi-file-pdf"></i> Export PDF
        </a>
        <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                <i class="bi bi-trash"></i> Delete
            </button>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Customer Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> {{ $invoice->customer->name }}</p>
                @if($invoice->customer->email)
                    <p><strong>Email:</strong> {{ $invoice->customer->email }}</p>
                @endif
                @if($invoice->customer->phone)
                    <p><strong>Phone:</strong> {{ $invoice->customer->phone }}</p>
                @endif
                @if($invoice->customer->address)
                    <p><strong>Address:</strong> {{ $invoice->customer->address }}</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Invoice Status</h5>
            </div>
            <div class="card-body">
                <p><span class="badge bg-{{ $invoice->status == 'paid' ? 'success' : ($invoice->status == 'draft' ? 'secondary' : 'warning') }}">{{ ucfirst($invoice->status) }}</span></p>
                @if($invoice->due_date)
                    <p><strong>Due Date:</strong> {{ $invoice->due_date->format('d M Y') }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">
        <h5 class="mb-0">Invoice Items</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Description</th>
                        <th class="text-end">Quantity</th>
                        <th class="text-end">Unit Price</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->items as $item)
                    <tr>
                        <td>{{ $item->item_name }}</td>
                        <td>{{ $item->description ?? '-' }}</td>
                        <td class="text-end">{{ $item->quantity }}</td>
                        <td class="text-end">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        <td class="text-end">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 offset-md-6">
                <div class="mb-2">
                    <strong>Subtotal:</strong>
                    <span class="float-end">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="mb-2">
                    <strong>Tax (10%):</strong>
                    <span class="float-end">Rp {{ number_format($invoice->tax, 0, ',', '.') }}</span>
                </div>
                <hr>
                <div class="mb-0">
                    <h5><strong>Total:</strong>
                        <span class="float-end">Rp {{ number_format($invoice->total, 0, ',', '.') }}</span>
                    </h5>
                </div>
            </div>
        </div>
    </div>
</div>

@if($invoice->notes)
<div class="card mb-3">
    <div class="card-header">
        <h5 class="mb-0">Notes</h5>
    </div>
    <div class="card-body">
        {{ $invoice->notes }}
    </div>
</div>
@endif

<div class="row">
    <div class="col-md-12">
        <a href="{{ route('invoices.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>
</div>
@endsection
