<?php

namespace App\Services;

use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use App\Models\PurchaseOrderItem;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PurchaseOrderService
{
    public function createFromPR(PurchaseRequest $pr, array $data, array $items)
    {
        return DB::transaction(function () use ($pr, $data, $items) {
            $year = Carbon::now()->format('Y');
            $count = PurchaseOrder::whereYear('created_at', $year)->count() + 1;
            $poNumber = 'PO-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

            $taxRate = $data['tax_rate'] ?? 0.11; // Default 11% VAT
            $discount = $data['discount'] ?? 0;

            $po = PurchaseOrder::create([
                'po_number' => $poNumber,
                'purchase_request_id' => $pr->id,
                'vendor_id' => $data['vendor_id'],
                'created_by' => Auth::id(),
                'order_date' => now(),
                'expected_delivery' => $data['expected_delivery'],
                'amount' => 0,
                'tax' => 0,
                'discount' => $discount,
                'grand_total' => 0,
                'status' => 'Sent',
                'payment_terms' => $data['payment_terms'] ?? null,
                'delivery_address' => $data['delivery_address'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            $totalAmount = 0;
            foreach ($items as $item) {
                $subtotal = $item['quantity'] * $item['unit_price'];
                $totalAmount += $subtotal;

                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'product_id' => $item['product_id'],
                    'description' => $item['description'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $subtotal,
                ]);
            }

            $taxAmount = $totalAmount * $taxRate;
            $grandTotal = $totalAmount + $taxAmount - $discount;

            $po->update([
                'amount' => $totalAmount,
                'tax' => $taxAmount,
                'grand_total' => $grandTotal,
            ]);

            $pr->update(['status' => 'PO Created']);

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'Created',
                'module' => 'Purchase Order',
                'record_id' => $po->po_number,
                'description' => 'Purchase Order created from PR ' . $pr->pr_number,
            ]);

            return $po;
        });
    }
}
