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
            $table->foreignIdFor(User::class); // Quem registrou
            $table->foreignIdFor(Computer::class);
            $table->unsignedBigInteger('tec_1')->nullable();
            $table->unsignedBigInteger('tec_2')->nullable();
            $table->unsignedBigInteger('tec_3')->nullable();
            $table->foreign('tec_1')->references('id')->on('users');
            $table->foreign('tec_2')->references('id')->on('users');
            $table->foreign('tec_3')->references('id')->on('users');
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
