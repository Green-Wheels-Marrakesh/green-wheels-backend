<?php

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
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('responsable')
                ->after('date_end')
                ->nullable();
            $table->dateTime('pick_up_date')
                ->after('date_end')
                ->nullable();
            $table->string('pick_up_location')
                ->after('date_end')
                ->nullable();
            $table->string('booking_payment_status')
                ->after('date_end')
                ->nullable();
            $table->string('notes')
                ->after('date_end')
                ->nullable();
            $table->dropColumn([
                'child',
                'guaranty_price',
            ]);
            $table->dropConstrainedForeignId('bike_variant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'responsable',
                'pick_up_date',
                'pick_up_location',
                'booking_payment_status',
                'notes',
            ]);
            $table->boolean('child')
                ->after('date_end');
            $table->double('guaranty_price')
                ->after('date_end');
            $table->foreignId('bike_variant_id')
                ->after('client_id')
                ->constrained()
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }
};
