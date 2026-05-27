<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use PDF;
use Excel;
use App\Exports\InvoicesExport;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('customer')->latest()->paginate(15);
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $customers = Customer::all();
        return view('invoices.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string',
            'items.*.description' => 'nullable|string',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $invoice = Invoice::create([
            'invoice_number' => 'INV-' . now()->format('YmdHis'),
            'customer_id' => $validated['customer_id'],
            'invoice_date' => $validated['invoice_date'],
            'due_date' => $validated['due_date'],
            'status' => 'draft',
            'notes' => $validated['notes'] ?? null,
        ]);

        foreach ($validated['items'] as $item) {
            $total = $item['quantity'] * $item['unit_price'];
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'item_name' => $item['item_name'],
                'description' => $item['description'] ?? null,
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total' => $total,
            ]);
        }

        $invoice->calculateTotals();

        return redirect()->route('invoices.show', $invoice)->with('success', 'Invoice created successfully');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('customer', 'items');
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $customers = Customer::all();
        $invoice->load('customer', 'items');
        return view('invoices.edit', compact('invoice', 'customers'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.id' => 'nullable|exists:invoice_items,id',
            'items.*.item_name' => 'required|string',
            'items.*.description' => 'nullable|string',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $invoice->update([
            'customer_id' => $validated['customer_id'],
            'invoice_date' => $validated['invoice_date'],
            'due_date' => $validated['due_date'],
            'notes' => $validated['notes'] ?? null,
        ]);

        // Delete items that are not in the request
        $itemIds = collect($validated['items'])
            ->filter(fn($item) => isset($item['id']))
            ->pluck('id')
            ->toArray();

        InvoiceItem::where('invoice_id', $invoice->id)
            ->whereNotIn('id', $itemIds)
            ->delete();

        // Update or create items
        foreach ($validated['items'] as $item) {
            $total = $item['quantity'] * $item['unit_price'];

            if (isset($item['id'])) {
                InvoiceItem::find($item['id'])->update([
                    'item_name' => $item['item_name'],
                    'description' => $item['description'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total' => $total,
                ]);
            } else {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'item_name' => $item['item_name'],
                    'description' => $item['description'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total' => $total,
                ]);
            }
        }

        $invoice->calculateTotals();

        return redirect()->route('invoices.show', $invoice)->with('success', 'Invoice updated successfully');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully');
    }

    public function print(Invoice $invoice)
    {
        $invoice->load('customer', 'items');
        return view('invoices.print', compact('invoice'));
    }

    public function exportPdf(Invoice $invoice)
    {
        $invoice->load('customer', 'items');
        $pdf = PDF::loadView('invoices.pdf', compact('invoice'));
        return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
    }

    public function dashboard()
    {
        $totalInvoices = Invoice::count();
        $totalRevenue = Invoice::sum('total');
        $paidInvoices = Invoice::where('status', 'paid')->count();
        $pendingInvoices = Invoice::whereIn('status', ['draft', 'sent'])->count();

        $revenueByMonth = Invoice::selectRaw('DATE_TRUNC(\'month\', invoice_date) as month, SUM(total) as revenue')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        return view('dashboard', compact('totalInvoices', 'totalRevenue', 'paidInvoices', 'pendingInvoices', 'revenueByMonth'));
    }

    public function exportExcel()
    {
        return Excel::download(new InvoicesExport, 'invoices-' . now()->format('Y-m-d') . '.xlsx');
    }
}
