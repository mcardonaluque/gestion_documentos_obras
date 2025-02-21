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
        Schema::create('documento_expediente_users', function (Blueprint $table) {
            $table->id();
            //$table->integer('id_doc');
            $table->foreignId('id_doc')->references('id')->on('documento_expedientes');
            //$table->integer('user_id');
            $table->foreignId('user_id')->references('id')->on('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documento_expediente_users');
    }
};
