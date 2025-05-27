<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('building_id')->nullable()->constrained()->onDelete('set null');            // $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('manager_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('type')->default('unit');
            $table->string('tenant_name');
            $table->string('tenant_email');
            $table->string('tenant_phone');
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->string('rent_amount')->nullable();
            $table->string('contract_type')->nullable();
            $table->string('reference')->unique();
            $table->string('status')->default('disponible');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
