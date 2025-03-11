<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('batch_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')
                ->constrained('batches')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->unsignedInteger('qty')
                ->nullable();
            $table->unsignedInteger('price')
                ->nullable();
            $table->unsignedInteger('remain_qty')
                ->nullable();
            $table->boolean('is_refunded')
                ->nullable();
            $table->foreignId('storage_id')
                ->nullable()
                ->constrained('storages')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_product');
    }
};
