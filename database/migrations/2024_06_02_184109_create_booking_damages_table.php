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
        Schema::create('booking_damages', function (Blueprint $table) {
            $table->id();
            $table->string('damage_type')
                ->nullable();
            $table->dateTime('damage_date')
                ->default(Carbon::now());
            $table->double('damage_estimated_price')
                ->default(0);
            $table->text('damage_description')
                ->nullable();
            $table->foreignId('booking_detail_id')
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
        Schema::dropIfExists('booking_damages');
    }
};
