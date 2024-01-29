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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();

            $table->text('text');
            $table->integer('rating')->nullable();

            $table->timestamps();

            $table->unsignedBigInteger('item_id')->nullable();          //!!!!!!!   KISZEDNI MAJD A NULLABLET   !!!!!!!
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');

            $table->unsignedBigInteger('user_id')->nullable();          //!!!!!!!   KISZEDNI MAJD A NULLABLET   !!!!!!!
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
