<?php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InvoicesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Invoice::with('customer')->get();
    }

    public function headings(): array
    {
        return [
            'Invoice Number',
            'Customer Name',
            'Invoice Date',
            'Due Date',
            'Subtotal',
            'Tax',
            'Total',
            'Status',
        ];
    }

    public function map($invoice): array
    {
        return [
            $invoice->invoice_number,
            $invoice->customer->name,
            $invoice->invoice_date->format('Y-m-d'),
            $invoice->due_date ? $invoice->due_date->format('Y-m-d') : '-',
            $invoice->subtotal,
            $invoice->tax,
            $invoice->total,
            $invoice->status,
        ];
    }
}
