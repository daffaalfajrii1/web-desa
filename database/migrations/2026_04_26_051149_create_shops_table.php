<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_category_id')->nullable()->constrained('shop_categories')->nullOnDelete();

            $table->string('title');
            $table->string('slug')->unique();
            $table->string('main_image')->nullable();

            $table->text('excerpt')->nullable();
            $table->longText('description')->nullable();

            $table->decimal('price', 15, 2)->default(0);
            $table->integer('stock')->nullable();

            $table->string('status')->default('available'); // available, out_of_stock
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);

            $table->string('whatsapp')->nullable();
            $table->string('seller_name')->nullable();
            $table->string('location')->nullable();

            $table->unsignedBigInteger('views')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();

            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};