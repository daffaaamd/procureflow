@extends('layouts.app')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center">
    <div>
        <div class="flex items-center gap-2">
            <h1 class="text-2xl font-bold text-slate-900">{{ $po->po_number }}</h1>
            <span class="badge badge-{{ strtolower(str_replace(' ', '', $po->status)) }} text-sm px-3 py-1">{{ $po->status }}</span>
        </div>
        <p class="mt-1 text-sm text-slate-500">Ordered on {{ $po->order_date }} by {{ $po->buyer->name }}</p>
    </div>
    <div class="mt-4 sm:mt-0 flex gap-2">
        @if(in_array($po->status, ['Sent', 'Partially Received']))
            <a href="{{ route('gr.create', ['po_id' => $po->id]) }}" class="btn btn-primary">Receive Goods (GR)</a>
        @endif
        <button class="btn btn-secondary" onclick="window.print()">
            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
            Print
        </button>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Document Link -->
        @if($po->purchaseRequest)
        <div class="bg-blue-50 border border-blue-200 rounded p-4 flex items-center justify-between">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                <span class="text-sm text-blue-900">Originated from Purchase Request: <strong>{{ $po->purchaseRequest->pr_number }}</strong></span>
            </div>
            <a href="{{ route('pr.show', $po->purchaseRequest) }}" class="text-sm font-medium text-blue-700 hover:text-blue-900">View PR</a>
        </div>
        @endif

        <div class="card p-0">
            <div class="px-6 py-4 border-b border-slate-200">
                <h3 class="text-lg font-medium text-slate-900">Purchase Order Details</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-sm font-medium text-slate-500 uppercase tracking-wider mb-2">Vendor</h4>
                    <div class="flex items-center">
                        @if($po->vendor->logo)
                        <img src="{{ $po->vendor->logo }}" class="h-12 w-12 rounded-full object-cover mr-4" alt="">
                        @endif
                        <div>
                            <div class="font-bold text-slate-900">{{ $po->vendor->name }}</div>
                            <div class="text-sm text-slate-500">{{ $po->vendor->email }} • {{ $po->vendor->phone }}</div>
                            <div class="text-sm text-slate-500 mt-1">{{ $po->vendor->address }}</div>
                        </div>
                    </div>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-slate-500 uppercase tracking-wider mb-2">Delivery Details</h4>
                    <div class="space-y-1 text-sm text-slate-900">
                        <p><strong>Expected Delivery:</strong> {{ $po->delivery_date }}</p>
                        <p><strong>Deliver To:</strong> Central Warehouse HQ</p>
                        <p><strong>Buyer:</strong> {{ $po->buyer->name }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card p-0">
            <div class="table-container border-0 shadow-none">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="table-header">Product</th>
                            <th class="table-header text-right">Ordered Qty</th>
                            <th class="table-header text-right">Received Qty</th>
                            <th class="table-header text-right">Unit Price</th>
                            <th class="table-header text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @foreach($po->items as $item)
                        <tr>
                            <td class="table-cell">
                                <div class="font-medium text-slate-900">{{ $item->product->name }}</div>
                                <div class="text-xs text-slate-500">{{ $item->product->sku }}</div>
                            </td>
                            <td class="table-cell text-right font-medium">{{ $item->quantity }} {{ $item->product->unit }}</td>
                            <td class="table-cell text-right">
                                <span class="{{ $item->received_quantity < $item->quantity ? 'text-orange-600' : 'text-green-600' }} font-bold">
                                    {{ $item->received_quantity }}
                                </span>
                            </td>
                            <td class="table-cell text-right text-slate-500">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                            <td class="table-cell text-right font-medium text-slate-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-slate-50">
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-right font-bold text-slate-900 uppercase tracking-wider text-xs">Total Amount</td>
                            <td class="px-6 py-4 text-right font-bold text-lg text-slate-900">Rp {{ number_format($po->grand_total, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        @if($po->terms)
        <div class="card p-6 bg-slate-50">
            <h4 class="text-sm font-medium text-slate-900 uppercase tracking-wider mb-2">Terms & Conditions</h4>
            <p class="text-sm text-slate-600 whitespace-pre-line">{{ $po->terms }}</p>
        </div>
        @endif

    </div>

    <div class="space-y-6">
        <div class="card p-6">
            <h3 class="text-lg font-medium text-slate-900 mb-4">Related Documents</h3>
            <ul class="space-y-4">
                @if($po->purchaseRequest)
                <li class="flex justify-between items-center text-sm">
                    <div class="flex items-center text-slate-500">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        PR
                    </div>
                    <a href="{{ route('pr.show', $po->purchaseRequest) }}" class="font-medium text-primary-600">{{ $po->purchaseRequest->pr_number }}</a>
                </li>
                @endif
                <!-- Placeholder for future GRs and Invoices -->
                <li class="flex justify-between items-center text-sm">
                    <div class="flex items-center text-slate-500">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>
                        GRs
                    </div>
                    <span class="text-slate-400 font-medium">Pending...</span>
                </li>
                <li class="flex justify-between items-center text-sm">
                    <div class="flex items-center text-slate-500">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z" /></svg>
                        Invoice
                    </div>
                    <span class="text-slate-400 font-medium">Pending...</span>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
