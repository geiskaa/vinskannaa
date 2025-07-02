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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');

            $table->string('order_id')->unique();

            $table->enum('status', ['pending', 'paid', 'expired', 'canceled'])->default('pending');

            // Status proses pesanan internal
            $table->enum('order_status', ['menunggu_konfirmasi', 'diproses', "dikirim", 'selesai', 'dibatalkan'])->default('menunggu_konfirmasi');

            // Total belanja & ongkir
            $table->decimal('total_amount', 12, 2);
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->decimal('tax_cost', 12, 2)->default(0);

            // Metode pengiriman atau pengambilan
            $table->string('shipping_method');

            // Alamat kirim
            $table->text('shipping_address');

            // Informasi pembayaran
            $table->string('payment_type')->nullable();
            $table->string('payment_token')->nullable();

            // Catatan pembeli
            $table->text('notes')->nullable();

            // Timestamp status
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('dikirim_at')->nullable();

            // Alasan pembatalan (jika ada)
            $table->text('cancel_reason')->nullable();

            $table->timestamps();

            // Foreign Key
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
