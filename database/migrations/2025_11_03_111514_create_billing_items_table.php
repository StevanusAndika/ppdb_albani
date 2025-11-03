<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('billing_items')) {
            Schema::create('billing_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('billing_master_id')->constrained('billing_master')->onDelete('cascade');
                $table->string('item_name');
                $table->text('description')->nullable();
                $table->decimal('amount', 15, 2);
                $table->integer('quantity')->default(1);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('billing_items');
    }
};
