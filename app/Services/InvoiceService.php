<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\PurchaseOrder;
use App\Models\GoodsReceipt;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InvoiceService
{
    public function createInvoice(array $data)
    {
        return DB::transaction(function () use ($data) {
            $invoice = Invoice::create([
                'invoice_number' => $data['invoice_number'],
                'vendor_id' => $data['vendor_id'],
                'purchase_order_id' => $data['purchase_order_id'],
                'goods_receipt_id' => $data['goods_receipt_id'] ?? null,
                'invoice_date' => $data['invoice_date'],
                'due_date' => $data['due_date'],
                'amount' => $data['amount'],
                'notes' => $data['notes'] ?? null,
            ]);

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'Created',
                'module' => 'Invoice',
                'record_id' => $invoice->invoice_number,
                'description' => 'Invoice created.',
            ]);

            return $invoice;
        });
    }

    public function performThreeWayMatch(Invoice $invoice)
    {
        // 3-way match: PO Quantity == GR Quantity == Invoice Quantity (Amount)
        $po = $invoice->purchaseOrder;
        $gr = $invoice->goodsReceipt;

        if (!$po || !$gr) {
            $invoice->update(['verification_status' => 'Mismatched']);
            return false;
        }

        // Simplistic check for demo: Total Invoice Amount == PO Grand Total
        $match = ($invoice->amount == $po->grand_total);
        
        $status = $match ? 'Matched' : 'Mismatched';
        
        $invoice->update([
            'verification_status' => $status,
            'verified_by' => Auth::id(),
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Verified',
            'module' => 'Invoice',
            'record_id' => $invoice->invoice_number,
            'description' => '3-Way Match performed. Result: ' . $status,
        ]);

        return $match;
    }
}
