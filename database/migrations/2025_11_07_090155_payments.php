<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_code')->unique();
            $table->foreignId('registration_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->enum('payment_method', ['cash', 'xendit' , 'bank_transfer']);
            $table->enum('status', ['pending', 'waiting_payment', 'waiting_verification', 'processing', 'success', 'failed', 'expired', 'lunas'])->default('pending');
            $table->string('xendit_id')->nullable();
            $table->string('xendit_external_id')->nullable();
            $table->text('xendit_response')->nullable();
            $table->text('admin_notes')->nullable();
            $table->string('payment_proof')->nullable(); // For storing payment proof file path
            $table->string('sender_name')->nullable(); // For bank transfer sender name
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'payment_method']);
            $table->index(['registration_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
