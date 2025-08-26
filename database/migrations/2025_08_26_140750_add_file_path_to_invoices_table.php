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
        Schema::table('invoices', function (Blueprint $table) {
        $table->string('file_path')->nullable()->after('status'); 
        $table->string('invoice_number')->nullable()->change(); 
        $table->decimal('total_amount', 10, 2)->nullable()->change(); 
        $table->date('due_date')->nullable()->change(); 
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    Schema::table('invoices', function (Blueprint $table) {
        $table->dropColumn('file_path');
        $table->string('invoice_number')->nullable(false)->change();
        $table->decimal('total_amount', 10, 2)->nullable(false)->change();
        $table->date('due_date')->nullable(false)->change();
    });
    }
};
