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
        Schema::create('scheduled_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('body');
            $table->unsignedBigInteger('created_by')->default(1); // default admin
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheduled_posts');
    }
};
