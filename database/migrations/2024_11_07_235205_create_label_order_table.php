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
        Schema::create('label_order', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('label_id');
            $table->unsignedBigInteger('order_id');
            $table->timestamps();

            $table->unique(['label_id', 'order_id']);
            $table->foreign('label_id')->references('id')->on('labels')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('label_order');
    }
};
