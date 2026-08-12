@extends('layouts.app')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Payment Voucher</h1>
        <p class="mt-1 text-sm text-slate-500">Processed on {{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }} by {{ $payment->processedBy->name }}</p>
    </div>
    <div class="mt-4 sm:mt-0 flex gap-2">
        <button class="btn btn-secondary" onclick="window.print()">Print Voucher</button>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 space-y-6">
        
        <div class="bg-white rounded-lg shadow border border-slate-200 overflow-hidden">
            <!-- Header -->
            <div class="bg-slate-800 px-6 py-6 text-white flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold tracking-wider">PROCUREFLOW</h2>
                    <p class="text-slate-400 text-sm">Payment Voucher</p>
                </div>
                <div class="text-right">
                    <div class="text-3xl font-light">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
                    <div class="text-green-400 text-sm font-medium mt-1 flex items-center justify-end">
                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        PAID SUCCESS
                    </div>
                </div>
            </div>

            <!-- Body -->
            <div class="p-8 space-y-8">
                
                <div class="grid grid-cols-2 gap-8 border-b border-slate-100 pb-8">
                    <div>
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">Paid To</h4>
                        <div class="font-bold text-slate-900 text-lg">{{ $payment->invoice->purchaseOrder->vendor->name }}</div>
                        <div class="text-slate-600 text-sm mt-1">
                            {{ $payment->invoice->purchaseOrder->vendor->address }}<br>
                            {{ $payment->invoice->purchaseOrder->vendor->email }}
                        </div>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">Payment Details</h4>
                        <table class="w-full text-sm">
                            <tr>
                                <td class="text-slate-500 py-1">Method:</td>
                                <td class="text-slate-900 font-medium text-right">{{ $payment->payment_method }}</td>
                            </tr>
                            <tr>
                                <td class="text-slate-500 py-1">Reference:</td>
                                <td class="text-slate-900 font-medium text-right">{{ $payment->payment_reference ?: 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="text-slate-500 py-1">Date:</td>
                                <td class="text-slate-900 font-medium text-right">{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-4">Invoice Reference</h4>
                    <table class="min-w-full text-sm border border-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-2 text-left font-medium text-slate-500">Invoice No.</th>
                                <th class="px-4 py-2 text-left font-medium text-slate-500">PO No.</th>
                                <th class="px-4 py-2 text-right font-medium text-slate-500">Amount Applied</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="px-4 py-3 border-t border-slate-200">
                                    <a href="{{ route('invoices.show', $payment->invoice) }}" class="text-primary-600 hover:underline">{{ $payment->invoice->invoice_number }}</a>
                                </td>
                                <td class="px-4 py-3 border-t border-slate-200">
                                    <a href="{{ route('po.show', $payment->invoice->purchaseOrder) }}" class="text-slate-600 hover:underline">{{ $payment->invoice->purchaseOrder->po_number }}</a>
                                </td>
                                <td class="px-4 py-3 border-t border-slate-200 text-right font-medium">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                @if($payment->notes)
                <div class="bg-slate-50 p-4 rounded text-sm text-slate-600">
                    <strong>Notes:</strong> {{ $payment->notes }}
                </div>
                @endif
                
            </div>
            
            <!-- Footer -->
            <div class="bg-slate-50 px-8 py-4 border-t border-slate-200 text-xs text-slate-500 flex justify-between">
                <span>Processed by: {{ $payment->processedBy->name }}</span>
                <span>System Generated Record</span>
            </div>
        </div>

    </div>

    <div class="space-y-6">
        <div class="card p-6">
            <h3 class="text-lg font-medium text-slate-900 mb-4">Vendor Bank Details</h3>
            <div class="bg-slate-50 rounded p-4 text-sm text-slate-700">
                <p><strong>Bank:</strong> {{ $payment->invoice->purchaseOrder->vendor->bank_name ?? 'N/A' }}</p>
                <p><strong>Account Name:</strong> {{ $payment->invoice->purchaseOrder->vendor->bank_account_name ?? 'N/A' }}</p>
                <p class="font-mono mt-1 text-slate-900 bg-white p-2 rounded border border-slate-200 text-center tracking-wider">{{ $payment->invoice->purchaseOrder->vendor->bank_account_number ?? 'N/A' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
