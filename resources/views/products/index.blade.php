@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Products Catalog</h1>
        <p class="mt-1 text-sm text-slate-500">Manage items available for procurement.</p>
    </div>
    <div>
        <button class="btn btn-primary" disabled>+ Add Product</button>
    </div>
</div>

<div class="card">
    <div class="p-4 border-b border-slate-200 bg-white">
        <form method="GET" action="{{ route('products.index') }}" class="flex gap-4 max-w-lg">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search product name or SKU..." class="input-field flex-1">
            <button type="submit" class="btn btn-secondary">Search</button>
        </form>
    </div>
    
    <div class="table-container">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th scope="col" class="table-header">Product</th>
                    <th scope="col" class="table-header">Category</th>
                    <th scope="col" class="table-header text-right">Standard Price</th>
                    <th scope="col" class="table-header">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @forelse($products as $product)
                <tr>
                    <td class="table-cell">
                        <div class="flex items-center">
                            @if($product->image)
                            <img class="h-10 w-10 rounded object-cover mr-4" src="{{ $product->image }}" alt="">
                            @endif
                            <div>
                                <div class="font-medium text-slate-900">{{ $product->name }}</div>
                                <div class="text-xs text-slate-500">{{ $product->sku }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="table-cell text-slate-500">{{ $product->category->name }}</td>
                    <td class="table-cell text-right font-medium">Rp {{ number_format($product->standard_price, 0, ',', '.') }} / {{ $product->unit }}</td>
                    <td class="table-cell">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $product->status == 'Active' ? 'bg-green-100 text-green-800' : 'bg-slate-100 text-slate-800' }}">
                            {{ $product->status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-slate-500">No products found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-slate-200">
        {{ $products->links() }}
    </div>
</div>
@endsection
