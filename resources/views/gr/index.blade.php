@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Goods Receipts</h1>
        <p class="mt-1 text-sm text-slate-500">Track items received at the warehouse.</p>
    </div>
</div>

<div class="card">
    <div class="p-4 border-b border-slate-200 bg-white sm:flex sm:items-center sm:justify-between">
        <form method="GET" action="{{ route('gr.index') }}" class="flex gap-4 w-full sm:w-auto">
            <div class="flex-1 sm:w-80">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search GR, PO Number or Vendor..." class="input-field w-full">
            </div>
            <div>
                <button type="submit" class="btn btn-secondary">Search</button>
            </div>
        </form>
    </div>
    
    <div class="table-container">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th scope="col" class="table-header">GR Number</th>
                    <th scope="col" class="table-header">Reference PO</th>
                    <th scope="col" class="table-header">Vendor</th>
                    <th scope="col" class="table-header">Received By</th>
                    <th scope="col" class="table-header text-right">Items Count</th>
                    <th scope="col" class="table-header relative">
                        <span class="sr-only">Actions</span>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @forelse($grs as $gr)
                <tr>
                    <td class="table-cell font-medium text-primary-600">
                        <a href="{{ route('gr.show', $gr) }}">{{ $gr->gr_number }}</a>
                        <div class="text-xs text-slate-500 mt-1">{{ $gr->receipt_date }}</div>
                    </td>
                    <td class="table-cell font-medium">
                        <a href="{{ route('po.show', $gr->purchaseOrder) }}" class="text-slate-900 hover:underline">{{ $gr->purchaseOrder->po_number }}</a>
                    </td>
                    <td class="table-cell">
                        <div class="text-sm font-medium text-slate-900">{{ $gr->purchaseOrder->vendor->name }}</div>
                    </td>
                    <td class="table-cell">{{ $gr->receivedBy->name }}</td>
                    <td class="table-cell text-right font-medium">
                        {{ $gr->items->count() }} items
                    </td>
                    <td class="table-cell text-right text-sm font-medium">
                        <a href="{{ route('gr.show', $gr) }}" class="text-primary-600 hover:text-primary-900">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                        No goods receipts found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-slate-200">
        {{ $grs->links() }}
    </div>
</div>
@endsection
