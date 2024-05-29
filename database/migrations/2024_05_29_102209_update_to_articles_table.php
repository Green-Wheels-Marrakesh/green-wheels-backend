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
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn([
                'qty_notification_setting',
            ]);
            $table->float('default_booking_rental_price')
                ->default(0)
                ->after('default_selling_price');
            $table->float('default_booking_tour_price')
                ->default(0)
                ->after('default_booking_rental_price');
            $table->float('default_guaranty_price')
                ->default(0)
                ->after('default_booking_tour_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->float('qty_notification_setting')
                ->default(0)
                ->after('default_selling_price');
            $table->dropColumn([
                'default_booking_rental_price',
                'default_booking_tour_price',
                'default_guaranty_price',
            ]);
        });
    }
};
