@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Invoices</h1>
        <p class="mt-1 text-sm text-slate-500">Manage vendor invoices and accounts payable.</p>
    </div>
</div>

<div class="card">
    <div class="p-4 border-b border-slate-200 bg-white sm:flex sm:items-center sm:justify-between">
        <form method="GET" action="{{ route('invoices.index') }}" class="flex gap-4">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Invoice or Vendor..." class="input-field">
            </div>
            <div>
                <select name="status" class="input-field" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                    <option value="Verified" {{ request('status') == 'Verified' ? 'selected' : '' }}>Verified</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-secondary">Filter</button>
            </div>
        </form>
    </div>
    
    <div class="table-container">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th scope="col" class="table-header">Invoice Number</th>
                    <th scope="col" class="table-header">Vendor</th>
                    <th scope="col" class="table-header">Due Date</th>
                    <th scope="col" class="table-header text-right">Amount</th>
                    <th scope="col" class="table-header">Match Status</th>
                    <th scope="col" class="table-header">Payment Status</th>
                    <th scope="col" class="table-header relative">
                        <span class="sr-only">Actions</span>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @forelse($invoices as $invoice)
                <tr>
                    <td class="table-cell font-medium text-primary-600">
                        <a href="{{ route('invoices.show', $invoice) }}">{{ $invoice->invoice_number }}</a>
                        <div class="text-xs text-slate-500 mt-1">Ref: <a href="{{ route('po.show', $invoice->purchaseOrder) }}" class="hover:underline">{{ $invoice->purchaseOrder->po_number }}</a></div>
                    </td>
                    <td class="table-cell">
                        <div class="text-sm font-medium text-slate-900">{{ $invoice->purchaseOrder->vendor->name }}</div>
                    </td>
                    <td class="table-cell">
                        <div class="text-sm text-slate-900">{{ $invoice->due_date }}</div>
                        @if(\Carbon\Carbon::parse($invoice->due_date)->isPast() && $invoice->payment_status != 'Paid')
                            <div class="text-xs text-red-600 font-medium">Overdue</div>
                        @endif
                    </td>
                    <td class="table-cell text-right font-medium text-slate-900">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
                    <td class="table-cell">
                        <span class="badge badge-{{ strtolower(str_replace(' ', '', $invoice->status)) }}">
                            {{ $invoice->status }}
                        </span>
                    </td>
                    <td class="table-cell">
                        <span class="badge badge-{{ strtolower(str_replace(' ', '', $invoice->payment_status)) }}">
                            {{ $invoice->payment_status }}
                        </span>
                    </td>
                    <td class="table-cell text-right text-sm font-medium">
                        <a href="{{ route('invoices.show', $invoice) }}" class="text-primary-600 hover:text-primary-900">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                        No invoices found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-slate-200">
        {{ $invoices->links() }}
    </div>
</div>
@endsection
