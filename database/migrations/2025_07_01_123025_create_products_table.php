<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();

            // Disini database untuk membuat segmentasi(section) dan kategori(category)
            // File Asli Disini ygy
            // $table->enum('section', ['men', 'women', 'kids']);
            // $table->enum('category', [
            //     'kemeja',
            //     'kaos',
            //     'jaket',
            //     'celana_panjang',
            //     'celana_pendek',
            //     'hoodie',
            //     'dress',
            //     'rok',
            //     'sweater'
            //]);

            // Disini segmentasi(section) dan kategori(category) baru
            $table->enum('section', [
                'Best Seller',
                'Flashsale',
                'New Collection',
            ]);
            $table->enum('category', [
                'Boquete',
                'Tanaman Hias',
                'Bingkai',
            ]);
            //=======================================================

            $table->decimal('price', 10, 2);
            $table->text('description')->nullable();
            $table->json('images')->nullable();
            $table->decimal('ratings')->default(0);
            $table->integer('stock')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
