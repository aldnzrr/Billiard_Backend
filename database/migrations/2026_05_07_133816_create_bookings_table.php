<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {

            $table->id();

            $table->string('name');
            $table->string('date');
            $table->string('time');
            $table->string('room_type');

            $table->integer('table_qty');
            $table->integer('playing_hour');

            $table->integer('total_price');

            $table->string('status')
                ->default('pending');

            $table->string('receipt_image')
                ->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};