<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'ai_chat_count')) {
                $table->integer('ai_chat_count')->default(0)->after('phone');
            }
            if (!Schema::hasColumn('users', 'ai_chat_date')) {
                $table->date('ai_chat_date')->nullable()->after('ai_chat_count');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['ai_chat_count', 'ai_chat_date']);
        });
    }
};
