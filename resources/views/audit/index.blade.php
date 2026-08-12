@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">System Audit Logs</h1>
        <p class="mt-1 text-sm text-slate-500">Track all user activities across the procurement system.</p>
    </div>
    <div class="flex gap-2">
        <button class="btn btn-secondary" onclick="window.print()">Export Log</button>
    </div>
</div>

<div class="card">
    <div class="p-4 border-b border-slate-200 bg-white sm:flex sm:items-center sm:justify-between">
        <form method="GET" action="{{ route('audit.index') }}" class="flex gap-4 w-full sm:w-auto">
            <div class="flex-1 sm:w-80">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search logs..." class="input-field w-full">
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
                    <th scope="col" class="table-header w-48">Timestamp</th>
                    <th scope="col" class="table-header">User</th>
                    <th scope="col" class="table-header">Action</th>
                    <th scope="col" class="table-header">Module</th>
                    <th scope="col" class="table-header">Description</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @php
                    $logs = \App\Models\AuditLog::with('user')->latest()->paginate(20);
                @endphp
                @forelse($logs as $log)
                <tr>
                    <td class="table-cell whitespace-nowrap text-sm text-slate-500">
                        {{ $log->created_at->format('Y-m-d H:i:s') }}
                    </td>
                    <td class="table-cell">
                        <div class="font-medium text-slate-900">{{ $log->user->name ?? 'System' }}</div>
                        <div class="text-xs text-slate-500">{{ $log->user->role ?? '-' }}</div>
                    </td>
                    <td class="table-cell">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-800">
                            {{ $log->action }}
                        </span>
                    </td>
                    <td class="table-cell text-sm font-medium text-slate-700">
                        {{ $log->module }}
                    </td>
                    <td class="table-cell text-sm text-slate-600">
                        {{ $log->description }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                        No audit logs found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-slate-200">
        {{ $logs->links() }}
    </div>
</div>
@endsection
