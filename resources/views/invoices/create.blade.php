@extends('layouts.app')

@section('content')
<div class="mb-6">
    <div class="flex items-center">
        <a href="{{ route('po.show', $po) }}" class="text-slate-500 hover:text-slate-700 mr-2">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-slate-900">Log Vendor Invoice</h1>
    </div>
    <p class="mt-1 text-sm text-slate-500">Logging invoice for PO: <a href="{{ route('po.show', $po) }}" class="text-primary-600">{{ $po->po_number }}</a></p>
</div>

<form action="{{ route('invoices.store') }}" method="POST">
    @csrf
    <input type="hidden" name="purchase_order_id" value="{{ $po->id }}">
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            
            <div class="card p-6 mb-6">
                <h3 class="text-lg font-medium text-slate-900 mb-4">Invoice Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Vendor Invoice Number</label>
                        <input type="text" name="invoice_number" required class="input-field" placeholder="INV-...">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Invoice Date</label>
                        <input type="date" name="invoice_date" required class="input-field">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Due Date</label>
                        <input type="date" name="due_date" required class="input-field">
                    </div>
                </div>
            </div>

            <div class="card p-6">
                <h3 class="text-lg font-medium text-slate-900 mb-4">Financial Details</h3>
                <p class="text-sm text-slate-500 mb-4">The subtotal is pulled from the Purchase Order (Rp {{ number_format($po->grand_total, 0, ',', '.') }}). Enter any applicable tax and discounts.</p>
                
                <div class="space-y-4 max-w-sm">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Tax Amount (Rp)</label>
                        <input type="number" name="tax_amount" value="0" min="0" class="input-field" onchange="calcTotal()">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Discount Amount (Rp)</label>
                        <input type="number" name="discount_amount" value="0" min="0" class="input-field" onchange="calcTotal()">
                    </div>
                    <div class="pt-4 border-t border-slate-200">
                        <div class="flex justify-between items-center">
                            <span class="font-medium text-slate-700">Calculated Total</span>
                            <span class="font-bold text-xl text-slate-900" id="calc-total">Rp {{ number_format($po->grand_total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>

        <div>
            <div class="card p-6 sticky top-6">
                <h3 class="text-lg font-medium text-slate-900 mb-4">Vendor Details</h3>
                <div class="flex items-center mb-4">
                    @if($po->vendor->logo)
                    <img src="{{ $po->vendor->logo }}" class="h-10 w-10 rounded-full object-cover mr-3" alt="">
                    @endif
                    <div>
                        <div class="font-bold text-slate-900">{{ $po->vendor->name }}</div>
                        <div class="text-xs text-slate-500">{{ $po->vendor->email }}</div>
                    </div>
                </div>
                <div class="text-sm text-slate-700 mb-6 border-t border-slate-200 pt-4">
                    <p><strong>Bank:</strong> {{ $po->vendor->bank_name ?? 'N/A' }}</p>
                    <p><strong>Account Name:</strong> {{ $po->vendor->bank_account_name ?? 'N/A' }}</p>
                    <p><strong>Account Number:</strong> {{ $po->vendor->bank_account_number ?? 'N/A' }}</p>
                </div>
                
                <button type="submit" class="btn btn-primary w-full justify-center">Save Invoice</button>
            </div>
        </div>
    </div>
</form>

<script>
    const subtotal = {{ $po->grand_total }};
    function calcTotal() {
        const tax = parseFloat(document.querySelector('input[name="tax_amount"]').value) || 0;
        const disc = parseFloat(document.querySelector('input[name="discount_amount"]').value) || 0;
        const total = subtotal + tax - disc;
        document.getElementById('calc-total').innerText = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(total);
    }
</script>
@endsection
