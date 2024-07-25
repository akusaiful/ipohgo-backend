<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MytravelCustomFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bravo_tours', function (Blueprint $table) {
            if (!Schema::hasColumn('bravo_tours', 'date_form_to')) {
                $table->string('date_form_to')->nullable();
            }
            if (!Schema::hasColumn('bravo_tours', 'min_age')) {
                $table->string('min_age')->nullable();
            }
            if (!Schema::hasColumn('bravo_tours', 'pickup')) {
                $table->string('pickup')->nullable();
            }
            if (!Schema::hasColumn('bravo_tours', 'wifi_available')) {
                $table->tinyInteger('wifi_available')->nullable();
            }

            $table->tinyInteger('paid_parking')->nullable();
            $table->tinyInteger('free_parking')->nullable();

        });

        Schema::table('bravo_hotels', function (Blueprint $table) {
            if (!Schema::hasColumn('bravo_hotels', 'badge_tags')) {
                $table->text('badge_tags')->nullable();
            }
        });

        Schema::table('bravo_hotel_translations', function (Blueprint $table) {
            if (!Schema::hasColumn('bravo_hotel_translations', 'badge_tags')) {
                $table->text('badge_tags')->nullable();
            }
        });

        Schema::table('bravo_cars', function (Blueprint $table) {
            if (!Schema::hasColumn('bravo_cars', 'specifications')) {
                $table->text('specifications')->nullable();
            }
        });
        Schema::table('bravo_car_translations', function (Blueprint $table) {
            if (!Schema::hasColumn('bravo_car_translations', 'specifications')) {
                $table->text('specifications')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
