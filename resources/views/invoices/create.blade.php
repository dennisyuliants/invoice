@extends('layouts.app')

@section('title', 'Create Invoice')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h1><i class="bi bi-file-earmark-plus"></i> Create New Invoice</h1>
    </div>
</div>

<form action="{{ route('invoices.store') }}" method="POST" id="invoiceForm">
    @csrf

    <div class="card mb-3">
        <div class="card-header">
            <h5 class="mb-0">Invoice Details</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="customer_id" class="form-label">Customer *</label>
                        <select class="form-select @error('customer_id') is-invalid @enderror" id="customer_id" name="customer_id" required>
                            <option value="">Select Customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="invoice_date" class="form-label">Invoice Date *</label>
                        <input type="date" class="form-control @error('invoice_date') is-invalid @enderror" id="invoice_date" name="invoice_date" value="{{ old('invoice_date', now()->format('Y-m-d')) }}" required>
                        @error('invoice_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="due_date" class="form-label">Due Date</label>
                        <input type="date" class="form-control @error('due_date') is-invalid @enderror" id="due_date" name="due_date" value="{{ old('due_date') }}">
                        @error('due_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">Notes</label>
                <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Items *</h5>
                <button type="button" class="btn btn-sm btn-primary" id="addItem">
                    <i class="bi bi-plus-circle"></i> Add Item
                </button>
            </div>
        </div>
        <div class="card-body">
            <div id="itemsContainer">
                <div class="item-row mb-3">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Item Name *</label>
                            <input type="text" class="form-control item-name" name="items[0][item_name]" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Description</label>
                            <input type="text" class="form-control item-description" name="items[0][description]">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Quantity *</label>
                            <input type="number" class="form-control item-quantity" name="items[0][quantity]" value="1" min="1" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Unit Price *</label>
                            <input type="number" class="form-control item-price" name="items[0][unit_price]" step="0.01" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Total</label>
                            <input type="text" class="form-control item-total" disabled>
                        </div>
                        <div class="col-md-12 mt-2">
                            <button type="button" class="btn btn-danger btn-sm removeItem" style="display:none;">
                                <i class="bi bi-trash"></i> Remove
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 offset-md-6">
                    <div class="mb-2">
                        <strong>Subtotal:</strong>
                        <span id="subtotal" class="float-end">Rp 0</span>
                    </div>
                    <div class="mb-2">
                        <strong>Tax (10%):</strong>
                        <span id="tax" class="float-end">Rp 0</span>
                    </div>
                    <hr>
                    <div class="mb-0">
                        <h5><strong>Total:</strong>
                            <span id="total" class="float-end">Rp 0</span>
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <button type="submit" class="btn btn-success btn-lg">
                <i class="bi bi-check-circle"></i> Save Invoice
            </button>
            <a href="{{ route('invoices.index') }}" class="btn btn-secondary btn-lg">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>
</form>

@endsection

@section('scripts')
<script>
    let itemCount = 1;

    function updateCalculations() {
        let subtotal = 0;
        
        document.querySelectorAll('.item-row').forEach(row => {
            const qty = parseFloat(row.querySelector('.item-quantity').value) || 0;
            const price = parseFloat(row.querySelector('.item-price').value) || 0;
            const total = qty * price;
            
            row.querySelector('.item-total').value = 'Rp ' + total.toLocaleString('id-ID', {minimumFractionDigits: 0});
            subtotal += total;
        });

        const tax = subtotal * 0.10;
        const total = subtotal + tax;

        document.getElementById('subtotal').textContent = 'Rp ' + subtotal.toLocaleString('id-ID', {minimumFractionDigits: 0});
        document.getElementById('tax').textContent = 'Rp ' + tax.toLocaleString('id-ID', {minimumFractionDigits: 0});
        document.getElementById('total').textContent = 'Rp ' + total.toLocaleString('id-ID', {minimumFractionDigits: 0});
    }

    document.getElementById('addItem').addEventListener('click', function() {
        const container = document.getElementById('itemsContainer');
        const newRow = document.querySelector('.item-row').cloneNode(true);
        
        newRow.querySelectorAll('input').forEach((input, index) => {
            input.name = input.name.replace(/\[\d+\]/g, '[' + itemCount + ']');
            if (input.classList.contains('item-quantity')) {
                input.value = 1;
            } else if (!input.classList.contains('item-total')) {
                input.value = '';
            }
        });

        const removeBtn = newRow.querySelector('.removeItem');
        removeBtn.style.display = 'inline-block';
        removeBtn.addEventListener('click', function() {
            newRow.remove();
            updateCalculations();
        });

        container.appendChild(newRow);
        itemCount++;
        updateCalculations();
    });

    document.getElementById('itemsContainer').addEventListener('input', function(e) {
        if (e.target.classList.contains('item-quantity') || e.target.classList.contains('item-price')) {
            updateCalculations();
        }
    });

    updateCalculations();
</script>
@endsection
