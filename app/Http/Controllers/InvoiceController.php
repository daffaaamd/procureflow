<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\PurchaseOrder;
use App\Services\InvoiceService;

class InvoiceController extends Controller
{
    protected $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    public function index(Request $request)
    {
        $query = Invoice::with('purchaseOrder.vendor')->latest();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('purchaseOrder.vendor', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $invoices = $query->paginate(15);
        return view('invoices.index', compact('invoices'));
    }

    public function create(Request $request)
    {
        if (!$request->has('po_id')) {
            return redirect()->route('po.index')->withErrors('Please select a Purchase Order to invoice against.');
        }

        $po = PurchaseOrder::with('vendor', 'items')->findOrFail($request->po_id);
        
        return view('invoices.create', compact('po'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'invoice_number' => 'required|string|unique:invoices,invoice_number',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'tax_amount' => 'required|numeric|min:0',
            'discount_amount' => 'required|numeric|min:0',
        ]);

        $po = PurchaseOrder::findOrFail($request->purchase_order_id);
        
        $invoice = $this->invoiceService->create($po, $request->all());

        return redirect()->route('invoices.show', $invoice)->with('success', 'Invoice has been logged successfully.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('purchaseOrder.vendor', 'purchaseOrder.goodsReceipts');
        
        // 3-way matching logic for UI display
        $matching = $this->invoiceService->performThreeWayMatch($invoice);
        
        return view('invoices.show', compact('invoice', 'matching'));
    }

    public function verify(Request $request, Invoice $invoice)
    {
        $this->invoiceService->verify($invoice);
        return back()->with('success', 'Invoice verified successfully. It is now ready for payment.');
    }
}
