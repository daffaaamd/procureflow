<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('vendor_id')->constrained('vendors');
            $table->foreignId('purchase_order_id')->constrained('purchase_orders');
            $table->foreignId('goods_receipt_id')->nullable()->constrained('goods_receipts');
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->date('invoice_date');
            $table->date('due_date');
            $table->decimal('amount', 15, 2);
            $table->string('verification_status')->default('Pending'); // Pending, Matched, Mismatched
            $table->string('payment_status')->default('Unpaid'); // Unpaid, Partially Paid, Paid, Overdue
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_number')->unique();
            $table->foreignId('invoice_id')->constrained('invoices');
            $table->foreignId('processed_by')->constrained('users');
            $table->date('payment_date');
            $table->decimal('amount', 15, 2);
            $table->string('payment_method'); // Bank Transfer, Cash, Check, Credit Card
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('Completed');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('invoices');
    }
};
