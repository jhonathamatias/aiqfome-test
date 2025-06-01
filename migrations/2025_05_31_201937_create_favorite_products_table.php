<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Query\Expression;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('favorite_products', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(new Expression('gen_random_uuid()'));
            $table->uuid('client_id');
            $table->unsignedBigInteger('product_id');
            $table->string('title');
            $table->string('image');
            $table->float('price');
            $table->float('rating_rate')->nullable();
            $table->integer('rating_count')->nullable();
            $table->datetimes();

            $table->index(['client_id', 'product_id']);

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorite_products');
    }
};
