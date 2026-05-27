@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h1 class="mb-4"><i class="bi bi-graph-up"></i> Dashboard</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-muted">Total Invoices</h5>
                <h2 class="text-primary">{{ $totalInvoices }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-muted">Total Revenue</h5>
                <h2 class="text-success">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-muted">Paid Invoices</h5>
                <h2 class="text-info">{{ $paidInvoices }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-muted">Pending Invoices</h5>
                <h2 class="text-warning">{{ $pendingInvoices }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Monthly Revenue</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($revenueByMonth as $item)
                        <tr>
                            <td>{{ $item->month ? \Carbon\Carbon::parse($item->month)->format('M Y') : 'N/A' }}</td>
                            <td>Rp {{ number_format($item->revenue, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted">No data available</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <a href="{{ route('invoices.export-excel') }}" class="btn btn-success">
            <i class="bi bi-file-earmark-excel"></i> Export to Excel
        </a>
    </div>
</div>
@endsection
