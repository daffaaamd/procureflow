@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Purchase Orders</h1>
        <p class="mt-1 text-sm text-slate-500">Manage vendor orders and deliveries.</p>
    </div>
    <div>
        <a href="{{ route('po.create') }}" class="btn btn-primary">
            + Create PO
        </a>
    </div>
</div>

<div class="card">
    <div class="p-4 border-b border-slate-200 bg-white sm:flex sm:items-center sm:justify-between">
        <form method="GET" action="{{ route('po.index') }}" class="flex gap-4">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search PO or Vendor..." class="input-field">
            </div>
            <div>
                <select name="status" class="input-field" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                    <option value="Sent" {{ request('status') == 'Sent' ? 'selected' : '' }}>Sent</option>
                    <option value="Partially Received" {{ request('status') == 'Partially Received' ? 'selected' : '' }}>Partially Received</option>
                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                    <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
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
                    <th scope="col" class="table-header">PO Number</th>
                    <th scope="col" class="table-header">Vendor</th>
                    <th scope="col" class="table-header">Expected Delivery</th>
                    <th scope="col" class="table-header text-right">Amount</th>
                    <th scope="col" class="table-header">Status</th>
                    <th scope="col" class="table-header relative">
                        <span class="sr-only">Actions</span>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @forelse($pos as $po)
                <tr>
                    <td class="table-cell font-medium text-primary-600">
                        <a href="{{ route('po.show', $po) }}">{{ $po->po_number }}</a>
                        <div class="text-xs text-slate-500 mt-1">{{ $po->order_date }}</div>
                    </td>
                    <td class="table-cell">
                        <div class="flex items-center">
                            @if($po->vendor->logo)
                            <div class="flex-shrink-0 h-8 w-8 mr-3">
                                <img class="h-8 w-8 rounded-full object-cover" src="{{ $po->vendor->logo }}" alt="">
                            </div>
                            @endif
                            <div>
                                <div class="text-sm font-medium text-slate-900">{{ $po->vendor->name }}</div>
                                <div class="text-xs text-slate-500">{{ $po->buyer->name }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="table-cell">{{ $po->delivery_date }}</td>
                    <td class="table-cell text-right font-medium text-slate-900">Rp {{ number_format($po->grand_total, 0, ',', '.') }}</td>
                    <td class="table-cell">
                        <span class="badge badge-{{ strtolower(str_replace(' ', '', $po->status)) }}">
                            {{ $po->status }}
                        </span>
                    </td>
                    <td class="table-cell text-right text-sm font-medium">
                        <a href="{{ route('po.show', $po) }}" class="text-primary-600 hover:text-primary-900">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                        No purchase orders found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-slate-200">
        {{ $pos->links() }}
    </div>
</div>
@endsection
