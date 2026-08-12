<?php

namespace App\Services;

use App\Models\GoodsReceipt;
use App\Models\GoodsReceiptItem;
use App\Models\PurchaseOrder;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Exception;

class GoodsReceiptService
{
    public function receive(PurchaseOrder $po, array $itemsData, string $notes = null)
    {
        return DB::transaction(function () use ($po, $itemsData, $notes) {
            $year = Carbon::now()->format('Y');
            $count = GoodsReceipt::whereYear('created_at', $year)->count() + 1;
            $grNumber = 'GR-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

            $gr = GoodsReceipt::create([
                'gr_number' => $grNumber,
                'purchase_order_id' => $po->id,
                'receiver_id' => Auth::id(),
                'receipt_date' => now(),
                'notes' => $notes,
            ]);

            $allFullyReceived = true;
            $anyReceived = false;

            foreach ($itemsData as $itemData) {
                $poItem = $po->items()->find($itemData['po_item_id']);
                
                if (!$poItem) continue;

                $qtyReceivedBefore = GoodsReceiptItem::where('purchase_order_item_id', $poItem->id)->sum('quantity_received');
                $qtyOrdered = $poItem->quantity;
                $qtyRemaining = $qtyOrdered - $qtyReceivedBefore;

                if ($itemData['quantity_received'] > $qtyRemaining) {
                    throw new Exception("Received quantity cannot exceed ordered quantity for product ID " . $poItem->product_id);
                }

                if ($itemData['quantity_received'] > 0) {
                    $anyReceived = true;
                    GoodsReceiptItem::create([
                        'goods_receipt_id' => $gr->id,
                        'purchase_order_item_id' => $poItem->id,
                        'product_id' => $poItem->product_id,
                        'quantity_ordered' => $qtyOrdered,
                        'quantity_received' => $itemData['quantity_received'],
                        'quantity_rejected' => $itemData['quantity_rejected'] ?? 0,
                        'condition' => $itemData['condition'] ?? 'Good',
                        'notes' => $itemData['notes'] ?? null,
                    ]);
                }

                if ($qtyReceivedBefore + $itemData['quantity_received'] < $qtyOrdered) {
                    $allFullyReceived = false;
                }
            }

            if (!$anyReceived) {
                throw new Exception("No items were received.");
            }

            $po->update(['status' => $allFullyReceived ? 'Completed' : 'Partially Received']);

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'Created',
                'module' => 'Goods Receipt',
                'record_id' => $gr->gr_number,
                'description' => 'Goods Receipt created for PO ' . $po->po_number,
            ]);

            return $gr;
        });
    }
}
