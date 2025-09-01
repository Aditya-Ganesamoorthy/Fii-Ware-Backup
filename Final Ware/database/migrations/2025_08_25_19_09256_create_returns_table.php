<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->string('sales_id'); // Matches sales.sales_id type (varchar)
            $table->string('product_name'); // Matches sales.name type (varchar)
            $table->string('pid'); // Matches sales.pid type (varchar)
            $table->integer('quantity_returned');
            $table->text('reason')->nullable();
            $table->unsignedBigInteger('returned_by'); // Matches users.id type
            $table->string('status', 20)->default('pending');
            
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('returned_by')->references('id')->on('users')->onDelete('cascade');
            
            // Note: We can't create foreign keys for sales_id or pid because:
            // 1. sales.sales_id is not unique (multiple products per sale)
            // 2. pid references products.pid but it's better to use product_id
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('returns');
    }
};
