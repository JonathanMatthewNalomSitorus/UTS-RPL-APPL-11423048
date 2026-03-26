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
            // (c) EHR
            $table->text('diagnosis')->nullable();
            $table->text('prescription')->nullable();
            $table->text('lab_results')->nullable();
            // (f) Pembayaran
            $table->decimal('amount', 12, 2)->default(0);
            $table->enum('payment_status', ['unpaid', 'paid', 'insured'])->default('unpaid');
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            //
        });
    }
};
