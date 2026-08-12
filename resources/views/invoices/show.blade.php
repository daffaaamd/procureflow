@extends('layouts.app')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center">
    <div>
        <div class="flex items-center gap-2">
            <h1 class="text-2xl font-bold text-slate-900">{{ $invoice->invoice_number }}</h1>
            <span class="badge badge-{{ strtolower(str_replace(' ', '', $invoice->status)) }} text-sm px-3 py-1">{{ $invoice->status }}</span>
            <span class="badge badge-{{ strtolower(str_replace(' ', '', $invoice->payment_status)) }} text-sm px-3 py-1">{{ $invoice->payment_status }}</span>
        </div>
        <p class="mt-1 text-sm text-slate-500">Invoice Date: {{ $invoice->invoice_date }} | Due Date: <span class="font-medium {{ \Carbon\Carbon::parse($invoice->due_date)->isPast() && $invoice->payment_status != 'Paid' ? 'text-red-600' : 'text-slate-700' }}">{{ $invoice->due_date }}</span></p>
    </div>
    <div class="mt-4 sm:mt-0 flex gap-2">
        @if($invoice->status == 'Verified' && $invoice->payment_status != 'Paid' && Auth::user()->role == 'Finance')
            <a href="{{ route('payments.create', ['invoice_id' => $invoice->id]) }}" class="btn btn-primary">Process Payment</a>
        @endif
        <button class="btn btn-secondary" onclick="window.print()">Print Details</button>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 space-y-6">
        
        <!-- 3-Way Match Panel -->
        <div class="card p-6 {{ $matching['is_matched'] ? 'border-t-4 border-t-green-500' : 'border-t-4 border-t-red-500' }}">
            <h3 class="text-lg font-medium text-slate-900 mb-4 flex items-center">
                Three-Way Match Verification
                @if($matching['is_matched'])
                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Match Successful</span>
                @else
                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Discrepancy Found</span>
                @endif
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <!-- PO Box -->
                <div class="bg-slate-50 rounded p-4 border border-slate-200">
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Purchase Order</div>
                    <div class="text-sm font-medium text-slate-900 mb-2">Rp {{ number_format($matching['po_amount'], 0, ',', '.') }}</div>
                    <a href="{{ route('po.show', $invoice->purchaseOrder) }}" class="text-xs text-primary-600 hover:underline">{{ $invoice->purchaseOrder->po_number }}</a>
                </div>
                <!-- GR Box -->
                <div class="bg-slate-50 rounded p-4 border border-slate-200">
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Goods Received Value</div>
                    <div class="text-sm font-medium text-slate-900 mb-2">Rp {{ number_format($matching['gr_amount'], 0, ',', '.') }}</div>
                    <div class="text-xs text-slate-500">Based on received QTY * PO Unit Price</div>
                </div>
                <!-- Invoice Box -->
                <div class="bg-slate-50 rounded p-4 border border-slate-200">
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Invoice Subtotal</div>
                    <div class="text-sm font-medium text-slate-900 mb-2">Rp {{ number_format($matching['invoice_subtotal'], 0, ',', '.') }}</div>
                    <div class="text-xs text-slate-500">Without tax & discounts</div>
                </div>
            </div>

            @if(!$matching['is_matched'])
                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Verification Failed</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach($matching['discrepancies'] as $disc)
                                        <li>{{ $disc }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($invoice->status == 'Draft' && Auth::user()->role == 'Finance')
                <div class="border-t border-slate-200 pt-4 mt-4 text-right">
                    <form action="{{ route('invoices.verify', $invoice) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn {{ $matching['is_matched'] ? 'btn-primary' : 'btn-secondary' }}">
                            {{ $matching['is_matched'] ? 'Verify Invoice & Approve for Payment' : 'Force Verify (Override Discrepancies)' }}
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <div class="card p-0">
            <div class="px-6 py-4 border-b border-slate-200">
                <h3 class="text-lg font-medium text-slate-900">Financial Summary</h3>
            </div>
            <div class="p-6">
                <dl class="space-y-4 max-w-sm ml-auto">
                    <div class="flex justify-between text-sm">
                        <dt class="text-slate-500">Subtotal</dt>
                        <dd class="font-medium text-slate-900">Rp {{ number_format($matching['invoice_subtotal'], 0, ',', '.') }}</dd>
                    </div>
                    <div class="flex justify-between text-sm">
                        <dt class="text-slate-500">Tax</dt>
                        <dd class="font-medium text-slate-900">+ Rp {{ number_format($invoice->tax_amount, 0, ',', '.') }}</dd>
                    </div>
                    <div class="flex justify-between text-sm">
                        <dt class="text-slate-500">Discount</dt>
                        <dd class="font-medium text-red-600">- Rp {{ number_format($invoice->discount_amount, 0, ',', '.') }}</dd>
                    </div>
                    <div class="flex justify-between text-lg pt-4 border-t border-slate-200">
                        <dt class="font-bold text-slate-900">Total Due</dt>
                        <dd class="font-bold text-slate-900">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

    </div>

    <div class="space-y-6">
        <div class="card p-6">
            <h3 class="text-lg font-medium text-slate-900 mb-4">Vendor Payment Info</h3>
            <div class="flex items-center mb-4">
                @if($invoice->purchaseOrder->vendor->logo)
                <img src="{{ $invoice->purchaseOrder->vendor->logo }}" class="h-10 w-10 rounded-full object-cover mr-3" alt="">
                @endif
                <div class="font-bold text-slate-900">{{ $invoice->purchaseOrder->vendor->name }}</div>
            </div>
            <div class="bg-slate-50 rounded p-4 text-sm text-slate-700">
                <p class="mb-2 text-xs font-bold text-slate-500 uppercase tracking-wide">Bank Details</p>
                <p><strong>Bank:</strong> {{ $invoice->purchaseOrder->vendor->bank_name ?? 'N/A' }}</p>
                <p><strong>Account Name:</strong> {{ $invoice->purchaseOrder->vendor->bank_account_name ?? 'N/A' }}</p>
                <p class="font-mono mt-1 text-slate-900 bg-white p-2 rounded border border-slate-200 text-center text-base tracking-wider">{{ $invoice->purchaseOrder->vendor->bank_account_number ?? 'N/A' }}</p>
            </div>
        </div>
        
        <!-- Related GRs -->
        <div class="card p-6">
            <h3 class="text-lg font-medium text-slate-900 mb-4">Related Goods Receipts</h3>
            <ul class="space-y-3">
                @forelse($invoice->purchaseOrder->goodsReceipts as $gr)
                <li class="flex justify-between items-center text-sm border-b border-slate-100 pb-2 last:border-0 last:pb-0">
                    <a href="{{ route('gr.show', $gr) }}" class="font-medium text-primary-600 hover:underline">{{ $gr->gr_number }}</a>
                    <span class="text-slate-500">{{ $gr->receipt_date }}</span>
                </li>
                @empty
                <li class="text-sm text-slate-500">No goods receipts found.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
