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
        Schema::table('before_afters', function (Blueprint $table) {
            $table->string('treatment')->nullable()->after('title');
            $table->string('problem')->nullable()->after('treatment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('before_afters', function (Blueprint $table) {
            $table->dropColumn(['treatment', 'problem']);
        });
    }
};
