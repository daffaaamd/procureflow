@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-900">Good morning, {{ Auth::user()->name }}</h1>
    <p class="mt-1 text-sm text-slate-500">Here's what's happening with your procurement activities.</p>
</div>

<!-- KPI Cards -->
<div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 mb-8">
    <div class="card p-6">
        <dt class="text-sm font-medium text-slate-500 truncate">Total Purchase Requests</dt>
        <dd class="mt-2 text-3xl font-semibold text-slate-900">{{ number_format($metrics['total_prs']) }}</dd>
    </div>
    <div class="card p-6">
        <dt class="text-sm font-medium text-slate-500 truncate">Pending Approvals</dt>
        <dd class="mt-2 text-3xl font-semibold text-yellow-600">{{ number_format($metrics['pending_approvals']) }}</dd>
    </div>
    <div class="card p-6">
        <dt class="text-sm font-medium text-slate-500 truncate">Active Purchase Orders</dt>
        <dd class="mt-2 text-3xl font-semibold text-primary-600">{{ number_format($metrics['active_pos']) }}</dd>
    </div>
    <div class="card p-6">
        <dt class="text-sm font-medium text-slate-500 truncate">Outstanding Invoices</dt>
        <dd class="mt-2 text-3xl font-semibold text-red-600">{{ number_format($metrics['outstanding_invoices']) }}</dd>
    </div>
    <div class="card p-6 sm:col-span-2 lg:col-span-2">
        <dt class="text-sm font-medium text-slate-500 truncate">Total Procurement Value</dt>
        <dd class="mt-2 text-3xl font-semibold text-slate-900">Rp {{ number_format($metrics['total_value'], 0, ',', '.') }}</dd>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Column -->
    <div class="lg:col-span-2 space-y-8">
        
        <!-- Charts -->
        <div class="card p-6">
            <h3 class="text-lg leading-6 font-medium text-slate-900 mb-4">Procurement Spending Trend</h3>
            <div class="h-64 relative w-full">
                <canvas id="spendingChart"></canvas>
            </div>
        </div>

        <!-- Recent POs -->
        <div class="card">
            <div class="px-6 py-5 border-b border-slate-200">
                <h3 class="text-lg leading-6 font-medium text-slate-900">Recent Purchase Orders</h3>
            </div>
            <div class="table-container">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th scope="col" class="table-header">PO Number</th>
                            <th scope="col" class="table-header">Vendor</th>
                            <th scope="col" class="table-header text-right">Amount</th>
                            <th scope="col" class="table-header">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @foreach($recentPOs as $po)
                        <tr>
                            <td class="table-cell font-medium text-primary-600">
                                <a href="{{ route('po.show', $po) }}">{{ $po->po_number }}</a>
                            </td>
                            <td class="table-cell">
                                <div class="flex items-center">
                                    @if($po->vendor->logo)
                                    <div class="flex-shrink-0 h-8 w-8 mr-3">
                                        <img class="h-8 w-8 rounded-full object-cover" src="{{ $po->vendor->logo }}" alt="">
                                    </div>
                                    @endif
                                    <div class="text-sm text-slate-900">{{ $po->vendor->name }}</div>
                                </div>
                            </td>
                            <td class="table-cell text-right font-medium">Rp {{ number_format($po->grand_total, 0, ',', '.') }}</td>
                            <td class="table-cell">
                                <span class="badge badge-{{ strtolower(str_replace(' ', '', $po->status)) }}">
                                    {{ $po->status }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Sidebar Column -->
    <div class="space-y-8">
        
        <!-- Top Vendors -->
        <div class="card">
            <div class="px-6 py-5 border-b border-slate-200">
                <h3 class="text-lg leading-6 font-medium text-slate-900">Top Vendors</h3>
            </div>
            <ul class="divide-y divide-slate-200">
                @foreach($topVendors as $vendor)
                <li class="px-6 py-4 flex items-center">
                    <img class="h-10 w-10 rounded-full object-cover mr-4" src="{{ $vendor->logo ?? 'https://ui-avatars.com/api/?name='.urlencode($vendor->name) }}" alt="">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-slate-900">{{ $vendor->name }}</p>
                        <p class="text-xs text-slate-500">{{ $vendor->total_orders }} Orders • Rp {{ number_format($vendor->total_spending, 0, ',', '.') }}</p>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>

        <!-- Recent Activity Timeline -->
        <div class="card">
            <div class="px-6 py-5 border-b border-slate-200">
                <h3 class="text-lg leading-6 font-medium text-slate-900">Recent Activity</h3>
            </div>
            <div class="px-6 py-5">
                <div class="flow-root">
                    <ul class="-mb-8">
                        @foreach($recentActivity as $log)
                        <li>
                            <div class="relative pb-8">
                                @if(!$loop->last)
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-slate-200" aria-hidden="true"></span>
                                @endif
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center ring-8 ring-white">
                                            <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-slate-500">{{ $log->description }} <a href="#" class="font-medium text-slate-900">{{ $log->user->name ?? 'System' }}</a></p>
                                        </div>
                                        <div class="text-right text-xs whitespace-nowrap text-slate-500">
                                            <time datetime="{{ $log->created_at }}">{{ $log->created_at->diffForHumans() }}</time>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('spendingChart').getContext('2d');
        const data = {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Spending (Rp)',
                data: [120000000, 190000000, 150000000, 220000000, 180000000, 300000000],
                backgroundColor: 'rgba(14, 165, 233, 0.2)',
                borderColor: '#0ea5e9',
                borderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        };
        new Chart(ctx, {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [2, 4], color: '#e2e8f0' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    });
</script>
@endsection
