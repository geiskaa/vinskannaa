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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('label', 50); // Rumah, Kantor, Kost, dll
            $table->string('recipient_name');
            $table->string('phone', 20);
            $table->text('full_address');
            $table->string('city', 100);
            $table->string('state', 100);
            $table->string('postal_code', 10);
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            // Index untuk pencarian
            $table->index(['user_id', 'is_primary']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};