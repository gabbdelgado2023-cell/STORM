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
        Schema::create('organizations', function (Blueprint $table) {
            $table->id(); // BIGINT UNSIGNED PRIMARY KEY
            $table->string('name');
            $table->string('category')->nullable();
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();
            $table->foreignId('officer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropForeign(['officer_id']);
        });
        Schema::dropIfExists('organizations');
    }
};
