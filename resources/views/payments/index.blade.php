@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Payments</h1>
        <p class="mt-1 text-sm text-slate-500">Track outbound payments to vendors.</p>
    </div>
</div>

<div class="card">
    <div class="p-4 border-b border-slate-200 bg-white sm:flex sm:items-center sm:justify-between">
        <form method="GET" action="{{ route('payments.index') }}" class="flex gap-4 w-full sm:w-auto">
            <div class="flex-1 sm:w-80">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Payment Ref, Invoice or Vendor..." class="input-field w-full">
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
                    <th scope="col" class="table-header">Payment Date</th>
                    <th scope="col" class="table-header">Vendor</th>
                    <th scope="col" class="table-header">Reference</th>
                    <th scope="col" class="table-header">Method</th>
                    <th scope="col" class="table-header text-right">Amount</th>
                    <th scope="col" class="table-header relative">
                        <span class="sr-only">Actions</span>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @forelse($payments as $payment)
                <tr>
                    <td class="table-cell font-medium text-slate-900">
                        {{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}
                    </td>
                    <td class="table-cell">
                        <div class="text-sm font-medium text-slate-900">{{ $payment->invoice->purchaseOrder->vendor->name }}</div>
                        <div class="text-xs text-slate-500">Inv: <a href="{{ route('invoices.show', $payment->invoice) }}" class="hover:underline text-primary-600">{{ $payment->invoice->invoice_number }}</a></div>
                    </td>
                    <td class="table-cell">
                        <div class="text-sm text-slate-900">{{ $payment->payment_reference ?: '-' }}</div>
                    </td>
                    <td class="table-cell text-sm text-slate-500">{{ $payment->payment_method }}</td>
                    <td class="table-cell text-right font-medium text-green-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                    <td class="table-cell text-right text-sm font-medium">
                        <a href="{{ route('payments.show', $payment) }}" class="text-primary-600 hover:text-primary-900">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                        No payments found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-slate-200">
        {{ $payments->links() }}
    </div>
</div>
@endsection
