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
        Schema::create('tournaments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('type');
            $table->timestamp('scheduled_event');
            $table->decimal('admission_price', 8, 2);
            $table->string('status', 15)->default("activo");
            $table->json('result')->nullable();
            $table->timestamps();

            $table->foreign('type')->references('id')->on('type_tournaments');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournaments');
    }
};
