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
            $table->string('description')->nullable()->after('gtin_code');
            $table->string('cest')->nullable()->after('description');
            $table->string('cst_pis')->nullable()->after('cest');
            $table->string('cst_cofins')->nullable()->after('cst_pis');
            $table->string('cst_icms')->nullable()->after('cst_cofins');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gtins', function (Blueprint $table) {
            $table->dropColumn([
                'description',
                'cest',
                'cst_pis',
                'cst_cofins',
                'cst_icms',
            ]);
        });
    }
};
