@extends('layouts.app')

@section('title', 'Invoices')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h1><i class="bi bi-file-earmark-text"></i> Invoices</h1>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('invoices.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> New Invoice
        </a>
        <a href="{{ route('invoices.export-excel') }}" class="btn btn-success">
            <i class="bi bi-file-earmark-excel"></i> Export Excel
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Invoice Number</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $invoice)
                    <tr>
                        <td><strong>{{ $invoice->invoice_number }}</strong></td>
                        <td>{{ $invoice->customer->name }}</td>
                        <td>{{ $invoice->invoice_date->format('d M Y') }}</td>
                        <td>Rp {{ number_format($invoice->total, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge bg-{{ $invoice->status == 'paid' ? 'success' : ($invoice->status == 'draft' ? 'secondary' : 'warning') }}">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-sm btn-info" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-sm btn-warning" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="{{ route('invoices.print', $invoice) }}" class="btn btn-sm btn-secondary" title="Print">
                                <i class="bi bi-printer"></i>
                            </a>
                            <a href="{{ route('invoices.export-pdf', $invoice) }}" class="btn btn-sm btn-danger" title="Export PDF">
                                <i class="bi bi-file-pdf"></i>
                            </a>
                            <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No invoices found. <a href="{{ route('invoices.create') }}">Create one</a></td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    {{ $invoices->links() }}
</div>
@endsection
