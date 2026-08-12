@extends('layouts.app')

@section('content')
<div class="mb-6">
    <div class="flex items-center">
        <a href="{{ route('po.show', $po) }}" class="text-slate-500 hover:text-slate-700 mr-2">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-slate-900">Create Goods Receipt</h1>
    </div>
    <p class="mt-1 text-sm text-slate-500">Receiving goods against PO: <a href="{{ route('po.show', $po) }}" class="text-primary-600">{{ $po->po_number }}</a> ({{ $po->vendor->name }})</p>
</div>

<form action="{{ route('gr.store') }}" method="POST">
    @csrf
    <input type="hidden" name="purchase_order_id" value="{{ $po->id }}">
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="card p-6 mb-6">
                <h3 class="text-lg font-medium text-slate-900 mb-4">Receive Items</h3>
                <p class="text-sm text-slate-500 mb-4">Enter the actual quantity received in the warehouse.</p>
                
                <div class="table-container border-0 shadow-none">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="table-header">Product</th>
                                <th class="table-header text-right">Ordered Qty</th>
                                <th class="table-header text-right">Prev. Received</th>
                                <th class="table-header text-right">Pending</th>
                                <th class="table-header w-32 text-right">Receive Now</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @foreach($po->items as $idx => $item)
                            @php
                                $pending = $item->quantity - $item->received_quantity;
                            @endphp
                            <tr>
                                <td class="table-cell">
                                    <input type="hidden" name="items[{{ $idx }}][po_item_id]" value="{{ $item->id }}">
                                    <div class="font-medium text-slate-900">{{ $item->product->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $item->product->sku }}</div>
                                </td>
                                <td class="table-cell text-right">{{ $item->quantity }}</td>
                                <td class="table-cell text-right text-green-600">{{ $item->received_quantity }}</td>
                                <td class="table-cell text-right font-medium {{ $pending > 0 ? 'text-orange-600' : 'text-slate-500' }}">{{ $pending }}</td>
                                <td class="table-cell text-right">
                                    @if($pending > 0)
                                        <input type="number" name="items[{{ $idx }}][received_quantity]" min="0" max="{{ $pending }}" value="{{ $pending }}" class="input-field" required>
                                    @else
                                        <input type="number" name="items[{{ $idx }}][received_quantity]" value="0" class="input-field bg-slate-100 cursor-not-allowed" readonly>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>

        <div>
            <div class="card p-6 sticky top-6">
                <h3 class="text-lg font-medium text-slate-900 mb-4">Receipt Details</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Delivery Note / Resi Number</label>
                        <input type="text" name="delivery_note_number" class="input-field" placeholder="E.g. DN-2023-001">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Notes / Condition</label>
                        <textarea name="notes" rows="3" class="input-field" placeholder="Condition of goods, missing items, etc."></textarea>
                    </div>

                    <div class="pt-4 border-t border-slate-200">
                        <button type="submit" class="btn btn-primary w-full justify-center">Confirm Receipt</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
