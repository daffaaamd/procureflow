<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Exception;

class PaymentService
{
    public function makePayment(Invoice $invoice, array $data)
    {
        return DB::transaction(function () use ($invoice, $data) {
            $totalPaid = Payment::where('invoice_id', $invoice->id)->sum('amount');
            $remaining = $invoice->amount - $totalPaid;

            if ($data['amount'] > $remaining) {
                throw new Exception("Payment amount exceeds remaining balance.");
            }

            $year = Carbon::now()->format('Y');
            $count = Payment::whereYear('created_at', $year)->count() + 1;
            $payNumber = 'PAY-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

            $payment = Payment::create([
                'payment_number' => $payNumber,
                'invoice_id' => $invoice->id,
                'processed_by' => Auth::id(),
                'payment_date' => $data['payment_date'],
                'amount' => $data['amount'],
                'payment_method' => $data['payment_method'],
                'reference_number' => $data['reference_number'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            $totalPaid += $data['amount'];
            if ($totalPaid >= $invoice->amount) {
                $invoice->update(['payment_status' => 'Paid']);
                // If fully paid, close the PO & PR
                if ($invoice->purchaseOrder) {
                    $invoice->purchaseOrder->purchaseRequest->update(['status' => 'Closed']);
                }
            } else {
                $invoice->update(['payment_status' => 'Partially Paid']);
            }

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'Created',
                'module' => 'Payment',
                'record_id' => $payment->payment_number,
                'description' => 'Payment made for Invoice ' . $invoice->invoice_number,
            ]);

            return $payment;
        });
    }
}
