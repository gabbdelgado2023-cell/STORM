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
        Schema::table('events', function (Blueprint $table) {
            $table->string('location')->after('date');
            $table->decimal('budget', 10, 2)->nullable()->after('location');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('organization_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['location', 'budget', 'status']);
        });
    }
};