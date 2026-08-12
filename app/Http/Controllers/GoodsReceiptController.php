<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GoodsReceipt;
use App\Models\PurchaseOrder;
use App\Services\GoodsReceiptService;
use Illuminate\Support\Facades\Auth;

class GoodsReceiptController extends Controller
{
    protected $grService;

    public function __construct(GoodsReceiptService $grService)
    {
        $this->grService = $grService;
    }

    public function index(Request $request)
    {
        $query = GoodsReceipt::with('purchaseOrder.vendor', 'receivedBy')->latest();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('gr_number', 'like', "%{$search}%")
                  ->orWhereHas('purchaseOrder', function($q) use ($search) {
                      $q->where('po_number', 'like', "%{$search}%")
                        ->orWhereHas('vendor', function($v) use ($search) {
                            $v->where('name', 'like', "%{$search}%");
                        });
                  });
        }

        $grs = $query->paginate(15);
        return view('gr.index', compact('grs'));
    }

    public function create(Request $request)
    {
        if (!$request->has('po_id')) {
            // Need a PO to receive against
            return redirect()->route('po.index')->withErrors('Please select a Purchase Order to receive against.');
        }

        $po = PurchaseOrder::with('items.product', 'vendor')->findOrFail($request->po_id);
        
        if (!in_array($po->status, ['Sent', 'Partially Received'])) {
            return redirect()->route('po.show', $po)->withErrors('This PO cannot receive goods at this time.');
        }

        return view('gr.create', compact('po'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'delivery_note_number' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.po_item_id' => 'required|exists:purchase_order_items,id',
            'items.*.received_quantity' => 'required|integer|min:0',
        ]);

        $po = PurchaseOrder::findOrFail($request->purchase_order_id);
        
        $gr = $this->grService->receiveGoods($po, $request->all(), $request->items);

        return redirect()->route('gr.show', $gr)->with('success', 'Goods Receipt has been created successfully.');
    }

    public function show(GoodsReceipt $gr)
    {
        $gr->load('items.poItem.product', 'purchaseOrder.vendor', 'receivedBy');
        return view('gr.show', compact('gr'));
    }
}
