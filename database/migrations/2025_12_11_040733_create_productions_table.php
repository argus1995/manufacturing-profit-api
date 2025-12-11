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
        Schema::create('productions', function (Blueprint $table) {
            $table->id();
            $table->string('batch_code');
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->date('production_date');
            $table->date('finished_date')->nullable();
            $table->integer('quantity');
            $table->string('unit');       
            $table->enum('status', ['in_progress', 'complete', 'failed'])
                ->default('in_progress');
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productions');
    }
};
