<?php

use App\Models\Computer;
use App\Models\User;
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
        Schema::create('service_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Computer::class);
            $table->foreignIdFor(User::class); // Tec 1
            $table->foreignIdFor(User::class)->nullable(); // Tec 2
            $table->foreignIdFor(User::class)->nullable(); // Tec 3
            $table->string('defect');
            $table->string('repair_note')->nullable()->default('Não informado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_orders');
    }
};
