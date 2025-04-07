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
        Schema::table('conversation_room', function (Blueprint $table) {
        $table->dropColumn('id');
        $table->primary(['conversation_id', 'user_id']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conversation_room', function (Blueprint $table) {
            $table->dropPrimary(['conversation_id', 'user_id']);
            $table->id();
        });
    }
};
