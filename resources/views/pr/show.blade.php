@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <div class="flex items-center gap-2">
            <h1 class="text-2xl font-bold text-slate-900">{{ $pr->pr_number }}</h1>
            <span class="badge badge-{{ strtolower(str_replace(' ', '', $pr->status)) }} text-sm px-3 py-1">{{ $pr->status }}</span>
        </div>
        <p class="mt-1 text-sm text-slate-500">Requested on {{ $pr->request_date }} by {{ $pr->requester->name }} ({{ $pr->department->name }})</p>
    </div>
    <div class="flex gap-2">
        @if($pr->status === 'Approved' && in_array(Auth::user()->role, ['Admin', 'Procurement']))
            <a href="{{ route('po.create', ['pr_id' => $pr->id]) }}" class="btn btn-primary">Create PO</a>
        @endif
        <button class="btn btn-secondary" onclick="window.print()">Print / Save PDF</button>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 space-y-6">
        <div class="card p-0">
            <div class="px-6 py-4 border-b border-slate-200">
                <h3 class="text-lg font-medium text-slate-900">Requested Items</h3>
            </div>
            <div class="table-container border-0 shadow-none">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="table-header">Product</th>
                            <th class="table-header text-right">Qty</th>
                            <th class="table-header text-right">Est. Price</th>
                            <th class="table-header text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @foreach($pr->items as $item)
                        <tr>
                            <td class="table-cell">
                                <div class="flex items-center">
                                    @if($item->product->image)
                                    <img src="{{ $item->product->image }}" class="h-10 w-10 rounded object-cover mr-3" alt="">
                                    @endif
                                    <div>
                                        <div class="font-medium text-slate-900">{{ $item->product->name }}</div>
                                        <div class="text-xs text-slate-500">{{ $item->product->sku }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="table-cell text-right">{{ $item->quantity }} {{ $item->product->unit }}</td>
                            <td class="table-cell text-right">Rp {{ number_format($item->estimated_price, 0, ',', '.') }}</td>
                            <td class="table-cell text-right font-medium text-slate-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-slate-50">
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-right font-bold text-slate-900 uppercase tracking-wider text-xs">Total Amount</td>
                            <td class="px-6 py-4 text-right font-bold text-lg text-slate-900">Rp {{ number_format($pr->total_amount, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="card p-6">
            <h3 class="text-lg font-medium text-slate-900 mb-4">Justification</h3>
            <div class="bg-slate-50 rounded p-4 text-slate-700">
                {{ $pr->purpose }}
            </div>
            @if($pr->notes)
            <h4 class="text-sm font-medium text-slate-900 mt-4 mb-2">Notes</h4>
            <p class="text-sm text-slate-600">{{ $pr->notes }}</p>
            @endif
        </div>
    </div>

    <div class="space-y-6">
        <div class="card p-6">
            <h3 class="text-lg font-medium text-slate-900 mb-4">Request Info</h3>
            <dl class="space-y-4 text-sm">
                <div class="flex justify-between">
                    <dt class="text-slate-500">Required Date</dt>
                    <dd class="font-medium text-slate-900">{{ $pr->required_date }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-500">Priority</dt>
                    <dd class="font-medium">
                        <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $pr->priority == 'Urgent' ? 'bg-red-100 text-red-800' : ($pr->priority == 'High' ? 'bg-orange-100 text-orange-800' : 'bg-slate-100 text-slate-800') }}">
                            {{ $pr->priority }}
                        </span>
                    </dd>
                </div>
            </dl>
        </div>

        <div class="card p-6">
            <h3 class="text-lg font-medium text-slate-900 mb-4">Approval Timeline</h3>
            <div class="flow-root">
                <ul class="-mb-8">
                    <li>
                        <div class="relative pb-8">
                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-slate-200" aria-hidden="true"></span>
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                    <div>
                                        <p class="text-sm text-slate-500">Created by <span class="font-medium text-slate-900">{{ $pr->requester->name }}</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    @foreach($pr->approvals as $approval)
                    <li>
                        <div class="relative pb-8">
                            @if(!$loop->last)
                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-slate-200" aria-hidden="true"></span>
                            @endif
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full {{ $approval->status == 'Approved' ? 'bg-green-500' : 'bg-red-500' }} flex items-center justify-center ring-8 ring-white">
                                        @if($approval->status == 'Approved')
                                            <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                        @else
                                            <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        @endif
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                    <div>
                                        <p class="text-sm text-slate-500">{{ $approval->status }} by <span class="font-medium text-slate-900">{{ $approval->approver->name }}</span></p>
                                        @if($approval->comments)
                                        <p class="text-sm text-slate-600 mt-1">"{{ $approval->comments }}"</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                    @if(in_array($pr->status, ['PO Created', 'Closed']))
                    <li>
                        <div class="relative pb-8">
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                    <div>
                                        <p class="text-sm text-slate-500">Converted to Purchase Order</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endif
                </ul>
            </div>
        </div>

        <!-- Approval Actions -->
        @if($pr->status === 'Submitted' && in_array(Auth::user()->role, ['Manager', 'Admin']))
        <div class="card p-6 bg-slate-100 border-dashed border-2 border-slate-300">
            <h3 class="text-lg font-medium text-slate-900 mb-4 text-center">Manager Approval</h3>
            <div class="flex gap-3">
                <form action="{{ route('pr.approve', $pr) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="btn btn-primary w-full justify-center">Approve</button>
                </form>
                <button type="button" onclick="document.getElementById('reject-form').classList.toggle('hidden')" class="btn btn-danger flex-1 justify-center">Reject</button>
            </div>
            <form action="{{ route('pr.reject', $pr) }}" method="POST" id="reject-form" class="hidden mt-4">
                @csrf
                <textarea name="comments" rows="3" required class="input-field" placeholder="Reason for rejection..."></textarea>
                <button type="submit" class="btn btn-danger w-full justify-center mt-2">Submit Rejection</button>
            </form>
        </div>
        @endif

    </div>
</div>
@endsection
