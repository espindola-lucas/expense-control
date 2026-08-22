<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('movements', function (Blueprint $table) {
            $table->foreignId('recurring_payment_id')
                ->nullable()
                ->after('category_id')
                ->constrained()
                ->nullOnDelete();
        });

        // Idempotency guard for GenerateDueOccurrencesAction: a partial unique index
        // (only rows tied to a recurring payment) so the same occurrence can never be
        // inserted twice, even if the lazy generator runs concurrently.
        DB::statement(
            'CREATE UNIQUE INDEX movements_recurring_payment_id_movement_date_unique '
            .'ON movements (recurring_payment_id, movement_date) '
            .'WHERE recurring_payment_id IS NOT NULL'
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS movements_recurring_payment_id_movement_date_unique');

        Schema::table('movements', function (Blueprint $table) {
            $table->dropConstrainedForeignId('recurring_payment_id');
        });
    }
};
