<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['SINGLE_ROOM', 'SELF_CONTAINED', 'FLAT', 'DUPLEX'])->default('SINGLE_ROOM');
            $table->enum('mode', ['RENT', 'SALE'])->default('RENT');
            $table->enum('status', ['ACTIVE', 'INACTIVE'])->default('ACTIVE');
            $table->bigInteger('price');
            $table->integer('commission')->default(0);
            $table->bigInteger('total_price');
            $table->integer('rooms')->default(1);

            // Location
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->default('Nigeria');

            // Description and media
            $table->text('description')->nullable();
            $table->json('images')->nullable(); // Store as JSON array
            $table->string('video')->nullable();

            // Foreign key (if user/agent posted it)
            $table->foreignId('posted_by')->constrained('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
