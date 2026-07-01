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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('cpf')->unique();
            $table->string('password');
        });

        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('id_owner');
        });

        Schema::create('unitpeople', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('birthdate');
            $table->integer('id_unit');
        });

        Schema::create('unitvehicles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('color');
            $table->string('plate');
            $table->integer('id_unit');
        });
        
        Schema::create('unitpets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('race');
            $table->integer('id_unit');
        });

        Schema::create('walls', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('body');
            $table->dateTime('created_at');
        });

        Schema::create('walllikes', function (Blueprint $table) {
            $table->id();
            $table->integer('id_wall');
            $table->integer('id_user');
        });

        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('fileurl');
        });

        Schema::create('billets', function (Blueprint $table) {
            $table->id();
            $table->integer('id_unit');
            $table->string('title');
            $table->string('fileurl');
        });

        Schema::create('warnings', function (Blueprint $table) {
            $table->id();
            $table->integer('id_unit');
            $table->string('title');
            $table->string('body');
            $table->string('status')->default('IN_REVIEW');
            $table->dateTime('created_at');
            $table->text('photos')->nullable();
        });

        Schema::create('foundandlost', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default('LOST');
            $table->string('where');
            $table->string('description');
            $table->date('created_at');
            $table->text('photos')->nullable();
        });

        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->integer('allowed')->default(1);
            $table->string('title');
            $table->string('cover');
            $table->string('days');
            $table->time('start_time');
            $table->time('end_time');
        });

        Schema::create('areasdisableddays', function (Blueprint $table) {
            $table->id();
            $table->integer('id_area');
            $table->date('date');
        });

        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->integer('id_area');
            $table->integer('id_unit');
            $table->date('reservation_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('units');
        Schema::dropIfExists('unitpeople');
        Schema::dropIfExists('unitvehicles');
        Schema::dropIfExists('unitpets');
        Schema::dropIfExists('walls');
        Schema::dropIfExists('walllikes');
        Schema::dropIfExists('documents');
        Schema::dropIfExists('billets');
        Schema::dropIfExists('warnings');
        Schema::dropIfExists('foundandlost');
        Schema::dropIfExists('areas');
        Schema::dropIfExists('areasdisableddays');
        Schema::dropIfExists('reservations');
    }
};
