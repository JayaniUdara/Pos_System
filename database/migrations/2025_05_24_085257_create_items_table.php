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
        Schema::create('items', function (Blueprint $table) {
        $table->id();
        $table->string('item_code')->unique();
        $table->string('item_name');
        $table->date('mfd_date')->nullable();
        $table->date('exp_date');
        $table->string('company_name')->nullable();
        $table->string('image')->nullable();
        $table->integer('quantity');
        $table->decimal('cost_price', 10, 2);
        $table->decimal('selling_price', 10, 2);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
