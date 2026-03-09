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
        Schema::table('appointments', function (Blueprint $table) {
            // Add patient_id if it's missing
            if (!Schema::hasColumn('appointments', 'patient_id')) {
                $table->foreignId('patient_id')->nullable()->constrained()->onDelete('set null')->after('id');
            }

            // Drop obsolete payment columns if they exist
            $obsoleteColumns = ['amount', 'payment_id', 'payment_method'];
            foreach ($obsoleteColumns as $column) {
                if (Schema::hasColumn('appointments', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
