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
            if (!Schema::hasColumn('pengaturans', 'allow_operator_reupload')) {
                $table->boolean('allow_operator_reupload')->default(false)->after('is_registration_open');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaturans', function (Blueprint $table) {
            if (Schema::hasColumn('pengaturans', 'allow_operator_reupload')) {
                $table->dropColumn('allow_operator_reupload');
            }
        });
    }
};
