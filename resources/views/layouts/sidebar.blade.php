<aside class="hidden md:flex md:flex-shrink-0">
    <div class="flex flex-col w-64 border-r border-slate-200 bg-white">
        <div class="h-16 flex items-center px-6 border-b border-slate-200">
            <span class="text-xl font-bold text-slate-900 tracking-tight">ProcureFlow</span>
        </div>
        
        <div class="h-0 flex-1 flex flex-col overflow-y-auto">
            <nav class="flex-1 px-4 py-4 space-y-1">
                <p class="px-2 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Main Menu</p>
                
                <a href="{{ route('dashboard') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-slate-100 text-primary-700' : 'text-slate-700 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="mr-3 h-5 w-5 text-slate-400 group-hover:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('pr.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('pr.*') ? 'bg-slate-100 text-primary-700' : 'text-slate-700 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="mr-3 h-5 w-5 text-slate-400 group-hover:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Purchase Requests
                </a>

                @if(in_array(auth()->user()->role, ['Admin', 'Manager', 'Procurement']))
                <a href="{{ route('approvals.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('approvals.*') ? 'bg-slate-100 text-primary-700' : 'text-slate-700 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="mr-3 h-5 w-5 text-slate-400 group-hover:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Approvals
                </a>
                @endif

                @if(in_array(auth()->user()->role, ['Admin', 'Procurement', 'Manager', 'Warehouse', 'Finance']))
                <a href="{{ route('po.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('po.*') ? 'bg-slate-100 text-primary-700' : 'text-slate-700 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="mr-3 h-5 w-5 text-slate-400 group-hover:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    Purchase Orders
                </a>
                @endif

                @if(in_array(auth()->user()->role, ['Admin', 'Warehouse', 'Procurement', 'Finance']))
                <a href="{{ route('gr.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('gr.*') ? 'bg-slate-100 text-primary-700' : 'text-slate-700 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="mr-3 h-5 w-5 text-slate-400 group-hover:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                    Goods Receipts
                </a>
                @endif

                @if(in_array(auth()->user()->role, ['Admin', 'Finance', 'Manager', 'Procurement']))
                <a href="{{ route('invoices.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('invoices.*') ? 'bg-slate-100 text-primary-700' : 'text-slate-700 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="mr-3 h-5 w-5 text-slate-400 group-hover:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z" />
                    </svg>
                    Invoices
                </a>

                <a href="{{ route('payments.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('payments.*') ? 'bg-slate-100 text-primary-700' : 'text-slate-700 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="mr-3 h-5 w-5 text-slate-400 group-hover:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Payments
                </a>
                @endif

                <div class="pt-4 pb-2">
                    <p class="px-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Master Data</p>
                </div>

                <a href="{{ route('vendors.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('vendors.*') ? 'bg-slate-100 text-primary-700' : 'text-slate-700 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="mr-3 h-5 w-5 text-slate-400 group-hover:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Vendors
                </a>

                <a href="{{ route('products.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('products.*') ? 'bg-slate-100 text-primary-700' : 'text-slate-700 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="mr-3 h-5 w-5 text-slate-400 group-hover:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    Products
                </a>
                
                @if(in_array(auth()->user()->role, ['Admin', 'Manager']))
                <div class="pt-4 pb-2">
                    <p class="px-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">System</p>
                </div>
                
                <a href="{{ route('reports.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('reports.*') ? 'bg-slate-100 text-primary-700' : 'text-slate-700 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="mr-3 h-5 w-5 text-slate-400 group-hover:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Reports
                </a>
                
                <a href="{{ route('audit.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('audit.*') ? 'bg-slate-100 text-primary-700' : 'text-slate-700 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="mr-3 h-5 w-5 text-slate-400 group-hover:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Audit Logs
                </a>
                @endif
            </nav>
        </div>
    </div>
</aside>
