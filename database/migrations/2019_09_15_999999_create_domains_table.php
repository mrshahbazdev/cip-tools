<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Ye file Stancl/Tenancy package ne banai hogi.
class CreateDomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('domains', function (Blueprint $table) {
            $table->increments('id');
            $table->string('domain', 255)->unique();

            // Note: Tenancy ID ko string hona chahiye agar aap UUIDs use kar rahe hain.
            // Lekin agar aap projects table ko reference kar rahe hain, to uski ID type (int) se match karein.
            // Choonke projects ki ID int hai, hum yahan bhi int use karenge.
            $table->unsignedBigInteger('tenant_id');

            $table->timestamps();

            // ORIGINAL CODE: $table->foreign('tenant_id')->references('id')->on('tenants')...
            // FIX: tenants ki jagah projects table ko reference karein.
            $table->foreign('tenant_id')
                  ->references('id')->on('projects') // <--- Yahan Fix Hua
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('domains');
    }
}
