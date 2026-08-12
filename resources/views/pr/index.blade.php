@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Purchase Requests</h1>
        <p class="mt-1 text-sm text-slate-500">Manage and track your procurement requests.</p>
    </div>
    <div>
        <a href="{{ route('pr.create') }}" class="btn btn-primary">
            + Create Request
        </a>
    </div>
</div>

<div class="card">
    <div class="p-4 border-b border-slate-200 bg-white sm:flex sm:items-center sm:justify-between">
        <form method="GET" action="{{ route('pr.index') }}" class="flex gap-4">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search PR Number..." class="input-field">
            </div>
            <div>
                <select name="status" class="input-field" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                    <option value="Submitted" {{ request('status') == 'Submitted' ? 'selected' : '' }}>Submitted</option>
                    <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                    <option value="PO Created" {{ request('status') == 'PO Created' ? 'selected' : '' }}>PO Created</option>
                    <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
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
                    <th scope="col" class="table-header">PR Number</th>
                    <th scope="col" class="table-header">Requester</th>
                    <th scope="col" class="table-header">Required Date</th>
                    <th scope="col" class="table-header">Amount</th>
                    <th scope="col" class="table-header">Priority</th>
                    <th scope="col" class="table-header">Status</th>
                    <th scope="col" class="table-header relative">
                        <span class="sr-only">Actions</span>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @forelse($prs as $pr)
                <tr>
                    <td class="table-cell font-medium text-primary-600">
                        <a href="{{ route('pr.show', $pr) }}">{{ $pr->pr_number }}</a>
                        <div class="text-xs text-slate-500 mt-1">{{ $pr->request_date }}</div>
                    </td>
                    <td class="table-cell">
                        <div class="text-sm text-slate-900">{{ $pr->requester->name }}</div>
                        <div class="text-xs text-slate-500">{{ $pr->department->name }}</div>
                    </td>
                    <td class="table-cell">{{ $pr->required_date }}</td>
                    <td class="table-cell font-medium">Rp {{ number_format($pr->total_amount, 0, ',', '.') }}</td>
                    <td class="table-cell">
                        <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $pr->priority == 'Urgent' ? 'bg-red-100 text-red-800' : ($pr->priority == 'High' ? 'bg-orange-100 text-orange-800' : 'bg-slate-100 text-slate-800') }}">
                            {{ $pr->priority }}
                        </span>
                    </td>
                    <td class="table-cell">
                        <span class="badge badge-{{ strtolower(str_replace(' ', '', $pr->status)) }}">
                            {{ $pr->status }}
                        </span>
                    </td>
                    <td class="table-cell text-right text-sm font-medium">
                        <a href="{{ route('pr.show', $pr) }}" class="text-primary-600 hover:text-primary-900">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                        No purchase requests found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-slate-200">
        {{ $prs->links() }}
    </div>
</div>
@endsection
