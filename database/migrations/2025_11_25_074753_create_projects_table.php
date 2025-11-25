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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('super_admin_id')->constrained('users')->onDelete('cascade'); // Zaroori: User table se link
            $table->string('name')->unique();
            $table->string('subdomain')->unique();
            $table->boolean('pays_bonus')->default(false);
            $table->timestamp('trial_ends_at')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
