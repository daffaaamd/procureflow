@extends('layouts.app')

@section('content')
<div class="mb-6">
    <div class="flex items-center">
        <a href="{{ route('po.index') }}" class="text-slate-500 hover:text-slate-700 mr-2">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-slate-900">Create Purchase Order</h1>
    </div>
    @if($pr)
    <p class="mt-1 text-sm text-slate-500">From Purchase Request: <a href="{{ route('pr.show', $pr) }}" class="text-primary-600">{{ $pr->pr_number }}</a></p>
    @endif
</div>

<form action="{{ route('po.store') }}" method="POST">
    @csrf
    @if($pr)
        <input type="hidden" name="purchase_request_id" value="{{ $pr->id }}">
    @endif
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="card p-6 mb-6">
                <h3 class="text-lg font-medium text-slate-900 mb-4">Order Items</h3>
                
                <div class="table-container mb-4">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="table-header">Product</th>
                                <th class="table-header w-24">Qty</th>
                                <th class="table-header w-40">Unit Price (Rp)</th>
                                <th class="table-header w-40 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200" id="items-body">
                            @if($pr)
                                @foreach($pr->items as $idx => $item)
                                <tr>
                                    <td class="table-cell">
                                        <input type="hidden" name="items[{{ $idx }}][pr_item_id]" value="{{ $item->id }}">
                                        <input type="hidden" name="items[{{ $idx }}][product_id]" value="{{ $item->product_id }}">
                                        <div class="font-medium text-slate-900">{{ $item->product->name }}</div>
                                        <div class="text-xs text-slate-500">{{ $item->product->sku }}</div>
                                    </td>
                                    <td class="table-cell">
                                        <input type="number" name="items[{{ $idx }}][quantity]" value="{{ $item->quantity }}" class="input-field" onchange="calcSubtotal({{ $idx }})" required>
                                    </td>
                                    <td class="table-cell">
                                        <input type="number" name="items[{{ $idx }}][unit_price]" value="{{ $item->estimated_price }}" class="input-field" onchange="calcSubtotal({{ $idx }})" required>
                                    </td>
                                    <td class="table-cell text-right font-medium text-slate-900" id="subtotal-{{ $idx }}">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <!-- Dynamic Add Item goes here if PR is not provided -->
                                <tr>
                                    <td colspan="4" class="p-4 text-center text-sm text-red-500">Manual item entry without PR is not implemented in this demo view.</td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right font-medium text-slate-900">Total</td>
                                <td class="px-6 py-4 text-right font-bold text-slate-900" id="grand-total">
                                    Rp {{ $pr ? number_format($pr->total_amount, 0, ',', '.') : 0 }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
            <div class="card p-6">
                <h3 class="text-lg font-medium text-slate-900 mb-4">Terms & Conditions</h3>
                <textarea name="terms" rows="4" class="input-field" placeholder="Payment terms, delivery conditions, etc."></textarea>
            </div>
        </div>

        <div>
            <div class="card p-6 sticky top-6">
                <h3 class="text-lg font-medium text-slate-900 mb-4">Order Details</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Vendor</label>
                        <select name="vendor_id" class="input-field" required>
                            <option value="">Select Vendor...</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Expected Delivery Date</label>
                        <input type="date" name="delivery_date" required class="input-field" value="{{ $pr ? $pr->required_date : '' }}">
                    </div>

                    <div class="pt-4 border-t border-slate-200 flex gap-3 flex-col">
                        <button type="submit" name="action" value="draft" class="btn btn-secondary w-full justify-center">Save Draft</button>
                        <button type="submit" name="action" value="send" class="btn btn-primary w-full justify-center">Send to Vendor</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    function calcSubtotal(idx) {
        const qty = document.querySelector(`input[name="items[${idx}][quantity]"]`).value || 0;
        const price = document.querySelector(`input[name="items[${idx}][unit_price]"]`).value || 0;
        const subtotal = qty * price;
        document.getElementById(`subtotal-${idx}`).innerText = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(subtotal);
        calculateTotal();
    }

    function calculateTotal() {
        let total = 0;
        const inputs = document.querySelectorAll('input[name$="[unit_price]"]');
        inputs.forEach(input => {
            const idx = input.name.match(/\[(\d+)\]/)[1];
            const qty = document.querySelector(`input[name="items[${idx}][quantity]"]`).value || 0;
            total += (qty * input.value);
        });
        document.getElementById('grand-total').innerText = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(total);
    }
</script>
@endsection
