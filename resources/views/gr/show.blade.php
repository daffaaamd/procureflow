@extends('layouts.app')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">{{ $gr->gr_number }}</h1>
        <p class="mt-1 text-sm text-slate-500">Received on {{ $gr->receipt_date }} by {{ $gr->receivedBy->name }}</p>
    </div>
    <div class="mt-4 sm:mt-0 flex gap-2">
        @if(in_array(Auth::user()->role, ['Admin', 'Finance']) && $gr->purchaseOrder->status == 'Completed')
            <a href="{{ route('invoices.create', ['po_id' => $gr->purchaseOrder->id]) }}" class="btn btn-primary">Process Invoice</a>
        @endif
        <button class="btn btn-secondary" onclick="window.print()">Print Receipt</button>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 space-y-6">
        
        <!-- PO Link -->
        <div class="bg-blue-50 border border-blue-200 rounded p-4 flex items-center justify-between">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                <span class="text-sm text-blue-900">Received against Purchase Order: <strong>{{ $gr->purchaseOrder->po_number }}</strong></span>
            </div>
            <a href="{{ route('po.show', $gr->purchaseOrder) }}" class="text-sm font-medium text-blue-700 hover:text-blue-900">View PO</a>
        </div>

        <div class="card p-0">
            <div class="px-6 py-4 border-b border-slate-200">
                <h3 class="text-lg font-medium text-slate-900">Items Received</h3>
            </div>
            <div class="table-container border-0 shadow-none">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="table-header">Product</th>
                            <th class="table-header text-right">Received Qty</th>
                            <th class="table-header text-right">Condition</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @foreach($gr->items as $item)
                        <tr>
                            <td class="table-cell">
                                <div class="font-medium text-slate-900">{{ $item->poItem->product->name }}</div>
                                <div class="text-xs text-slate-500">{{ $item->poItem->product->sku }}</div>
                            </td>
                            <td class="table-cell text-right font-medium text-green-600">+{{ $item->received_quantity }} {{ $item->poItem->product->unit }}</td>
                            <td class="table-cell text-right text-sm text-slate-500">Good</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if($gr->notes)
        <div class="card p-6 bg-slate-50">
            <h4 class="text-sm font-medium text-slate-900 uppercase tracking-wider mb-2">Notes</h4>
            <p class="text-sm text-slate-600">{{ $gr->notes }}</p>
        </div>
        @endif
    </div>

    <div class="space-y-6">
        <div class="card p-6">
            <h3 class="text-lg font-medium text-slate-900 mb-4">Receipt Info</h3>
            <dl class="space-y-4 text-sm">
                <div class="flex justify-between">
                    <dt class="text-slate-500">Vendor</dt>
                    <dd class="font-medium text-slate-900 text-right">{{ $gr->purchaseOrder->vendor->name }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-500">Delivery Note</dt>
                    <dd class="font-medium text-slate-900">{{ $gr->delivery_note_number ?: '-' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-500">Location</dt>
                    <dd class="font-medium text-slate-900">Central Warehouse HQ</dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection
