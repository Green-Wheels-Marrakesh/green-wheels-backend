<?php

use Carbon\Carbon;
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
        Schema::create('booking_charges', function (Blueprint $table) {
            $table->id();
            $table->dateTime('charge_date')
                ->default(Carbon::now());
            $table->double('charge_price')
                ->default(0);
            $table->text('charge_description')
                ->nullable();
            $table->foreignId('booking_id')
                ->constrained()
                ->restrictOnDelete()
                ->cascadeOnUpdate();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_charges');
    }
};
