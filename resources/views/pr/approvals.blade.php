@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Pending Approvals</h1>
        <p class="mt-1 text-sm text-slate-500">Purchase Requests waiting for your manager approval.</p>
    </div>
</div>

<div class="grid grid-cols-1 gap-6">
    @forelse($prs as $pr)
    <div class="card p-6 border-l-4 border-l-yellow-400 flex flex-col md:flex-row md:items-center md:justify-between">
        <div class="flex-1">
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('pr.show', $pr) }}" class="text-lg font-bold text-primary-700 hover:underline">{{ $pr->pr_number }}</a>
                <span class="badge badge-submitted">Requires Approval</span>
                <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $pr->priority == 'Urgent' ? 'bg-red-100 text-red-800' : 'bg-slate-100 text-slate-800' }}">{{ $pr->priority }}</span>
            </div>
            <p class="text-sm text-slate-600 mb-2">{{ Str::limit($pr->purpose, 100) }}</p>
            <div class="flex items-center text-xs text-slate-500 gap-4">
                <span class="flex items-center"><svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg> {{ $pr->requester->name }} ({{ $pr->department->name }})</span>
                <span class="flex items-center"><svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg> Required: {{ $pr->required_date }}</span>
                <span class="flex items-center font-bold text-slate-900"><svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> Rp {{ number_format($pr->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>
        <div class="mt-4 md:mt-0 md:ml-6 flex gap-2 flex-shrink-0">
            <a href="{{ route('pr.show', $pr) }}" class="btn btn-secondary">View Details</a>
            <form action="{{ route('pr.approve', $pr) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">Approve</button>
            </form>
        </div>
    </div>
    @empty
    <div class="card p-12 text-center">
        <svg class="mx-auto h-12 w-12 text-slate-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="text-lg font-medium text-slate-900">All caught up!</h3>
        <p class="text-slate-500">There are no pending requests requiring your approval right now.</p>
    </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $prs->links() }}
</div>
@endsection
