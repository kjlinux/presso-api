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
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('customer_name');
            $table->decimal('amount', 10, 2);
            $table->integer('quantity');
            $table->enum('category', [
                'lavage-simple',
                'lavage-repassage',
                'repassage-seul',
                'nettoyage-sec',
                'costume',
                'robe-ceremonie'
            ]);
            $table->enum('payment_method', [
                'cash',
                'wave',
                'orange-money',
                'free-money',
                'bank',
                'check'
            ]);
            $table->json('inventory')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['en-attente', 'en-cours', 'termine', 'recupere'])->default('en-attente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
