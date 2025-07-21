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
        Schema::table('gtins', function (Blueprint $table) {
            $table->string('ncm')->nullable()->after('gtin_code');
            $table->string('cfop')->nullable()->after('ncm');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gtins', function (Blueprint $table) {
            $table->dropColumn('ncm');
            $table->dropColumn('cfop');
        });
    }
};
