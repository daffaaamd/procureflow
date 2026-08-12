<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseRequest;
use App\Models\PurchaseOrder;
use App\Models\GoodsReceipt;
use App\Models\Invoice;
use App\Models\Vendor;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $metrics = [
            'total_prs' => PurchaseRequest::count(),
            'pending_approvals' => PurchaseRequest::where('status', 'Submitted')->count(),
            'active_pos' => PurchaseOrder::whereIn('status', ['Sent', 'Partially Received'])->count(),
            'pending_deliveries' => PurchaseOrder::whereIn('status', ['Sent', 'Partially Received'])->count(),
            'outstanding_invoices' => Invoice::where('payment_status', '!=', 'Paid')->count(),
            'total_value' => PurchaseOrder::sum('grand_total'),
        ];

        $recentPOs = PurchaseOrder::with('vendor')->latest()->take(5)->get();
        $recentActivity = AuditLog::with('user')->latest()->take(5)->get();

        $topVendors = Vendor::withCount('purchaseOrders as total_orders')
            ->withSum('purchaseOrders as total_spending', 'grand_total')
            ->orderByDesc('total_spending')
            ->take(5)
            ->get();

        // Chart Data (Mocking monthly trend for UI demo purposes)
        $driver = DB::connection()->getDriverName();
        $monthExpression = $driver === 'sqlite'
            ? "strftime('%m', created_at) as months"
            : "DATE_FORMAT(created_at,'%M') as months";

        $monthlySpending = PurchaseOrder::select(
            DB::raw('sum(grand_total) as sums'), 
            DB::raw($monthExpression)
        )
        ->groupBy('months')
        ->get();

        if ($driver === 'sqlite') {
            $monthNames = [
                '01' => 'January', '02' => 'February', '03' => 'March',
                '04' => 'April', '05' => 'May', '06' => 'June',
                '07' => 'July', '08' => 'August', '09' => 'September',
                '10' => 'October', '11' => 'November', '12' => 'December',
            ];

            $monthlySpending = $monthlySpending->map(function ($item) use ($monthNames) {
                $item->months = $monthNames[$item->months] ?? $item->months;
                return $item;
            });
        }

        return view('dashboard', compact('metrics', 'recentPOs', 'recentActivity', 'topVendors', 'monthlySpending'));
    }
}
