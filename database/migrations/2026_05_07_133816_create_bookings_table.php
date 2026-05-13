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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('table_id')->constrained('tables')->onDelete('cascade'); // Konek ke meja
            $table->string('customer_name');
            $table->dateTime('start_time');
            $table->integer('duration'); // Dalam jam
            $table->integer('total_price');
            $table->enum('status', ['pending', 'paid', 'canceled'])->default('pending');
            $table->timestamps();
            $table->string('status')->default('pending'); // pending, waiting, paid, playing
            $table->string('receipt_image')->nullable(); // Simpan nama file foto struk
            $table->string('ticket_code')->unique()->nullable(); // Kode untuk barcode
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
