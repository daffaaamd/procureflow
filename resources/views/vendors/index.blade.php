@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Vendors</h1>
        <p class="mt-1 text-sm text-slate-500">Manage your supplier directory.</p>
    </div>
    <div>
        <button class="btn btn-primary" disabled>+ Add Vendor</button>
    </div>
</div>

<div class="card">
    <div class="p-4 border-b border-slate-200 bg-white">
        <form method="GET" action="{{ route('vendors.index') }}" class="flex gap-4 max-w-lg">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search vendor name, email, or category..." class="input-field flex-1">
            <button type="submit" class="btn btn-secondary">Search</button>
        </form>
    </div>
    
    <div class="table-container">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th scope="col" class="table-header">Vendor Name</th>
                    <th scope="col" class="table-header">Category</th>
                    <th scope="col" class="table-header">Contact</th>
                    <th scope="col" class="table-header">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @forelse($vendors as $vendor)
                <tr>
                    <td class="table-cell">
                        <div class="flex items-center">
                            @if($vendor->logo)
                            <img class="h-10 w-10 rounded-full object-cover mr-4" src="{{ $vendor->logo }}" alt="">
                            @endif
                            <div class="font-medium text-slate-900">{{ $vendor->name }}</div>
                        </div>
                    </td>
                    <td class="table-cell text-slate-500">{{ $vendor->category }}</td>
                    <td class="table-cell">
                        <div class="text-sm text-slate-900">{{ $vendor->email }}</div>
                        <div class="text-xs text-slate-500">{{ $vendor->phone }}</div>
                    </td>
                    <td class="table-cell">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $vendor->status == 'Active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $vendor->status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-slate-500">No vendors found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-slate-200">
        {{ $vendors->links() }}
    </div>
</div>
@endsection
