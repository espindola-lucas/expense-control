<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('personal_configurations', function (Blueprint $table) {
            $table->id();
            $table->date('start_counting')->nullable();
            $table->date('end_counting')->nullable();
            $table->integer('available_money');
            $table->string('month_available_money', length: 10);
            $table->integer('expense_percentage_limit');
            $table->foreignId('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS "personal_configurations" CASCADE');
    }
};
