@extends('layouts.app')

@section('content')
<div class="mb-6">
    <div class="flex items-center">
        <a href="{{ route('invoices.show', $invoice) }}" class="text-slate-500 hover:text-slate-700 mr-2">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-slate-900">Process Payment</h1>
    </div>
    <p class="mt-1 text-sm text-slate-500">Paying Invoice: <a href="{{ route('invoices.show', $invoice) }}" class="text-primary-600">{{ $invoice->invoice_number }}</a></p>
</div>

<form action="{{ route('payments.store') }}" method="POST">
    @csrf
    <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
    <input type="hidden" name="amount" value="{{ $invoice->total_amount }}"> <!-- Assuming full payment for simplicity -->
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            
            <!-- Payment Amount Display (Full Payment locked for now) -->
            <div class="bg-green-50 border border-green-200 rounded p-6 mb-6 text-center">
                <p class="text-sm font-medium text-green-800 uppercase tracking-wide mb-2">Amount to Pay</p>
                <div class="text-4xl font-bold text-green-900">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</div>
            </div>

            <div class="card p-6">
                <h3 class="text-lg font-medium text-slate-900 mb-4">Payment Details</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Payment Date</label>
                        <input type="date" name="payment_date" required class="input-field" value="{{ date('Y-m-d') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Payment Method</label>
                        <select name="payment_method" required class="input-field">
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Check">Check</option>
                            <option value="Corporate Card">Corporate Card</option>
                            <option value="Cash">Cash</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700">Bank Reference / Transaction ID</label>
                        <input type="text" name="payment_reference" class="input-field" placeholder="E.g. TRF-839210482">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700">Notes (Optional)</label>
                        <textarea name="notes" rows="2" class="input-field"></textarea>
                    </div>
                </div>
            </div>
            
        </div>

        <div>
            <div class="card p-6 sticky top-6">
                <h3 class="text-lg font-medium text-slate-900 mb-4">Vendor Bank Details</h3>
                <div class="flex items-center mb-4">
                    @if($invoice->purchaseOrder->vendor->logo)
                    <img src="{{ $invoice->purchaseOrder->vendor->logo }}" class="h-10 w-10 rounded-full object-cover mr-3" alt="">
                    @endif
                    <div class="font-bold text-slate-900">{{ $invoice->purchaseOrder->vendor->name }}</div>
                </div>
                
                <div class="bg-slate-50 rounded p-4 text-sm text-slate-700 mb-6">
                    <p><strong>Bank:</strong> {{ $invoice->purchaseOrder->vendor->bank_name ?? 'N/A' }}</p>
                    <p><strong>Account Name:</strong> {{ $invoice->purchaseOrder->vendor->bank_account_name ?? 'N/A' }}</p>
                    <p class="font-mono mt-2 text-slate-900 bg-white p-2 rounded border border-slate-200 text-center tracking-wider">{{ $invoice->purchaseOrder->vendor->bank_account_number ?? 'N/A' }}</p>
                </div>
                
                <button type="submit" class="btn btn-primary w-full justify-center">Confirm Payment</button>
            </div>
        </div>
    </div>
</form>
@endsection
