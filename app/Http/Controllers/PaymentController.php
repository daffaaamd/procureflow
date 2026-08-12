<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Invoice;
use App\Services\PaymentService;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index(Request $request)
    {
        $query = Payment::with('invoice.purchaseOrder.vendor', 'processedBy')->latest();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('payment_reference', 'like', "%{$search}%")
                  ->orWhereHas('invoice', function($q) use ($search) {
                      $q->where('invoice_number', 'like', "%{$search}%")
                        ->orWhereHas('purchaseOrder.vendor', function($v) use ($search) {
                            $v->where('name', 'like', "%{$search}%");
                        });
                  });
        }

        $payments = $query->paginate(15);
        return view('payments.index', compact('payments'));
    }

    public function create(Request $request)
    {
        if (!$request->has('invoice_id')) {
            return redirect()->route('invoices.index')->withErrors('Please select an Invoice to pay.');
        }

        $invoice = Invoice::with('purchaseOrder.vendor')->findOrFail($request->invoice_id);
        
        if ($invoice->status !== 'Verified' || $invoice->payment_status === 'Paid') {
            return redirect()->route('invoices.show', $invoice)->withErrors('This invoice is not eligible for payment.');
        }

        return view('payments.create', compact('invoice'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
            'payment_reference' => 'nullable|string',
            'amount' => 'required|numeric|min:1',
            'notes' => 'nullable|string',
        ]);

        $invoice = Invoice::findOrFail($request->invoice_id);
        
        $payment = $this->paymentService->processPayment($invoice, $request->all());

        return redirect()->route('payments.show', $payment)->with('success', 'Payment processed successfully.');
    }

    public function show(Payment $payment)
    {
        $payment->load('invoice.purchaseOrder.vendor', 'processedBy');
        return view('payments.show', compact('payment'));
    }
}
