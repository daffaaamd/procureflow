@extends('layouts.app')

@section('content')
<div class="mb-6">
    <div class="flex items-center">
        <a href="{{ route('pr.index') }}" class="text-slate-500 hover:text-slate-700 mr-2">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-slate-900">Create Purchase Request</h1>
    </div>
</div>

<form action="{{ route('pr.store') }}" method="POST" id="pr-form">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="card p-6 mb-6">
                <h3 class="text-lg font-medium text-slate-900 mb-4">Request Items</h3>
                
                <div class="table-container mb-4 overflow-visible">
                    <table class="min-w-full divide-y divide-slate-200" id="items-table">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="table-header">Product</th>
                                <th class="table-header w-24">Qty</th>
                                <th class="table-header w-40">Est. Price (Rp)</th>
                                <th class="table-header w-40">Subtotal</th>
                                <th class="table-header w-10"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200" id="items-body">
                            <!-- Items inserted by JS -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right font-medium text-slate-900">Total</td>
                                <td class="px-6 py-4 font-bold text-slate-900" id="grand-total">Rp 0</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <button type="button" onclick="addItem()" class="btn btn-secondary text-sm">
                    + Add Item
                </button>
            </div>
            
            <div class="card p-6">
                <h3 class="text-lg font-medium text-slate-900 mb-4">Justification</h3>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Purpose</label>
                    <textarea name="purpose" rows="3" required class="input-field" placeholder="Explain why this procurement is needed..."></textarea>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-slate-700">Additional Notes</label>
                    <textarea name="notes" rows="2" class="input-field" placeholder="Optional notes..."></textarea>
                </div>
            </div>
        </div>

        <div>
            <div class="card p-6 sticky top-6">
                <h3 class="text-lg font-medium text-slate-900 mb-4">Request Details</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Required Date</label>
                        <input type="date" name="required_date" required class="input-field">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Priority</label>
                        <select name="priority" class="input-field">
                            <option value="Normal">Normal</option>
                            <option value="High">High</option>
                            <option value="Urgent">Urgent</option>
                        </select>
                    </div>

                    <div class="pt-4 border-t border-slate-200 flex gap-3">
                        <button type="submit" name="action" value="draft" class="btn btn-secondary flex-1 justify-center">Save Draft</button>
                        <button type="submit" name="action" value="submit" class="btn btn-primary flex-1 justify-center">Submit Request</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    const products = @json($products);
    let itemIndex = 0;

    function addItem() {
        const tbody = document.getElementById('items-body');
        const tr = document.createElement('tr');
        
        let options = '<option value="">Select product...</option>';
        products.forEach(p => {
            options += `<option value="${p.id}" data-price="${p.standard_price}">${p.name} - ${p.sku}</option>`;
        });

        tr.innerHTML = `
            <td class="table-cell">
                <select name="items[${itemIndex}][product_id]" class="input-field" onchange="updatePrice(this, ${itemIndex})" required>
                    ${options}
                </select>
            </td>
            <td class="table-cell">
                <input type="number" name="items[${itemIndex}][quantity]" min="1" value="1" class="input-field" onchange="calcSubtotal(${itemIndex})" required>
            </td>
            <td class="table-cell">
                <input type="number" name="items[${itemIndex}][estimated_price]" class="input-field" onchange="calcSubtotal(${itemIndex})" required>
            </td>
            <td class="table-cell font-medium text-slate-900" id="subtotal-${itemIndex}">
                Rp 0
            </td>
            <td class="table-cell text-right">
                <button type="button" onclick="this.closest('tr').remove(); calculateTotal();" class="text-red-500 hover:text-red-700">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
        itemIndex++;
    }

    function updatePrice(select, idx) {
        const option = select.options[select.selectedIndex];
        const price = option.getAttribute('data-price') || 0;
        const input = document.querySelector(`input[name="items[${idx}][estimated_price]"]`);
        input.value = parseFloat(price);
        calcSubtotal(idx);
    }

    function calcSubtotal(idx) {
        const qty = document.querySelector(`input[name="items[${idx}][quantity]"]`).value || 0;
        const price = document.querySelector(`input[name="items[${idx}][estimated_price]"]`).value || 0;
        const subtotal = qty * price;
        document.getElementById(`subtotal-${idx}`).innerText = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(subtotal);
        calculateTotal();
    }

    function calculateTotal() {
        let total = 0;
        const inputs = document.querySelectorAll('input[name$="[estimated_price]"]');
        inputs.forEach(input => {
            const idx = input.name.match(/\[(\d+)\]/)[1];
            const qty = document.querySelector(`input[name="items[${idx}][quantity]"]`).value || 0;
            total += (qty * input.value);
        });
        document.getElementById('grand-total').innerText = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(total);
    }

    // Add first item by default
    document.addEventListener('DOMContentLoaded', addItem);
</script>
@endsection
