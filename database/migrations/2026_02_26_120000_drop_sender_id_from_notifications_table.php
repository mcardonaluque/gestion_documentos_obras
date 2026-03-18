<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('notifications', 'sender_id')) {
            return;
        }

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['sender_id']);
            $table->dropColumn('sender_id');
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('notifications', 'sender_id')) {
            return;
        }

        Schema::table('notifications', function (Blueprint $table) {
            $table->foreignId('sender_id')
                ->nullable()
                ->constrained('users');
        });
    }
};
