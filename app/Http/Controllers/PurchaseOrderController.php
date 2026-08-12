<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use App\Models\Vendor;
use App\Services\PurchaseOrderService;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderController extends Controller
{
    protected $poService;

    public function __construct(PurchaseOrderService $poService)
    {
        $this->poService = $poService;
    }

    public function index(Request $request)
    {
        $query = PurchaseOrder::with('vendor', 'buyer')->latest();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('po_number', 'like', "%{$search}%")
                  ->orWhereHas('vendor', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $pos = $query->paginate(15);
        return view('po.index', compact('pos'));
    }

    public function create(Request $request)
    {
        // Support creating PO directly from an approved PR
        $pr = null;
        if ($request->has('pr_id')) {
            $pr = PurchaseRequest::with('items.product')->findOrFail($request->pr_id);
            if ($pr->status !== 'Approved') {
                return redirect()->route('pr.show', $pr)->withErrors('Only approved PRs can be converted to POs.');
            }
        }
        
        $vendors = Vendor::where('status', 'Active')->get();
        return view('po.create', compact('vendors', 'pr'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'delivery_date' => 'required|date',
            'terms' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.pr_item_id' => 'nullable|exists:purchase_request_items,id',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $po = $this->poService->create($request->all(), $request->items);

        // Optionally send PO to vendor if requested
        if ($request->action == 'send') {
            $this->poService->sendToVendor($po);
            return redirect()->route('po.show', $po)->with('success', 'PO created and sent to Vendor successfully.');
        }

        return redirect()->route('po.show', $po)->with('success', 'PO created as Draft.');
    }

    public function show(PurchaseOrder $po)
    {
        $po->load('items.product', 'vendor', 'buyer', 'purchaseRequest');
        return view('po.show', compact('po'));
    }
}
