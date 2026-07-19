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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->after('name');
            $table->foreignId('skpd_id')->nullable()->after('email')->constrained('skpds')->onDelete('set null');
            $table->enum('role', ['admin', 'operator'])->default('operator')->after('skpd_id');
            $table->boolean('status')->default(true)->after('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['skpd_id']);
            $table->dropColumn(['username', 'skpd_id', 'role', 'status']);
        });
    }
};
