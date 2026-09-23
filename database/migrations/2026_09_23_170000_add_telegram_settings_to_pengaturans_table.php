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
        Schema::table('pengaturans', function (Blueprint $table) {
            if (!Schema::hasColumn('pengaturans', 'telegram_bot_token')) {
                $table->string('telegram_bot_token')->nullable()->after('allow_skpd_download_bukti_digital');
            }
            if (!Schema::hasColumn('pengaturans', 'telegram_chat_id')) {
                $table->string('telegram_chat_id')->nullable()->after('telegram_bot_token');
            }
            if (!Schema::hasColumn('pengaturans', 'telegram_notif_aktif')) {
                $table->boolean('telegram_notif_aktif')->default(false)->after('telegram_chat_id');
            }
            if (!Schema::hasColumn('pengaturans', 'telegram_template')) {
                $table->text('telegram_template')->nullable()->after('telegram_notif_aktif');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaturans', function (Blueprint $table) {
            $columnsToDrop = [];
            foreach (['telegram_bot_token', 'telegram_chat_id', 'telegram_notif_aktif', 'telegram_template'] as $col) {
                if (Schema::hasColumn('pengaturans', $col)) {
                    $columnsToDrop[] = $col;
                }
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
