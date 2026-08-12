<?php

namespace App\Services;

use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestItem;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PurchaseRequestService
{
    public function create(array $data, array $items)
    {
        return DB::transaction(function () use ($data, $items) {
            $year = Carbon::now()->format('Y');
            $count = PurchaseRequest::whereYear('created_at', $year)->count() + 1;
            $prNumber = 'PR-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

            $pr = PurchaseRequest::create([
                'pr_number' => $prNumber,
                'requester_id' => Auth::id(),
                'department_id' => Auth::user()->department_id,
                'request_date' => now(),
                'required_date' => $data['required_date'],
                'priority' => $data['priority'],
                'purpose' => $data['purpose'],
                'notes' => $data['notes'] ?? null,
                'status' => $data['action'] === 'submit' ? 'Submitted' : 'Draft',
                'total_amount' => 0,
            ]);

            $totalAmount = 0;
            foreach ($items as $item) {
                $subtotal = $item['quantity'] * $item['estimated_price'];
                $totalAmount += $subtotal;

                PurchaseRequestItem::create([
                    'purchase_request_id' => $pr->id,
                    'product_id' => $item['product_id'],
                    'description' => $item['description'] ?? null,
                    'quantity' => $item['quantity'],
                    'estimated_price' => $item['estimated_price'],
                    'subtotal' => $subtotal,
                ]);
            }

            $pr->update(['total_amount' => $totalAmount]);

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => $data['action'] === 'submit' ? 'Submitted' : 'Created',
                'module' => 'Purchase Request',
                'record_id' => $pr->pr_number,
                'description' => 'Purchase Request created.',
            ]);

            return $pr;
        });
    }

    public function approve(PurchaseRequest $pr, string $comments = null)
    {
        return DB::transaction(function () use ($pr, $comments) {
            $pr->update(['status' => 'Approved']);

            $pr->approvals()->create([
                'approver_id' => Auth::id(),
                'status' => 'Approved',
                'comments' => $comments,
            ]);

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'Approved',
                'module' => 'Purchase Request',
                'record_id' => $pr->pr_number,
                'description' => 'Purchase Request approved.',
            ]);

            return $pr;
        });
    }

    public function reject(PurchaseRequest $pr, string $comments)
    {
        return DB::transaction(function () use ($pr, $comments) {
            $pr->update(['status' => 'Rejected']);

            $pr->approvals()->create([
                'approver_id' => Auth::id(),
                'status' => 'Rejected',
                'comments' => $comments,
            ]);

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'Rejected',
                'module' => 'Purchase Request',
                'record_id' => $pr->pr_number,
                'description' => 'Purchase Request rejected.',
            ]);

            return $pr;
        });
    }
}
