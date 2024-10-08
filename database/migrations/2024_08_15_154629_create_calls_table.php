<?php

use App\Models\Location;
use App\Models\Technical;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('calls', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class); //Usuario que registrou
            $table->string('issue'); //Problema
            $table->string('request')->nullable()->default('Não informado'); //Quem solicitou
            $table->date('scheduling')->nullable();
            $table->foreignIdFor(Location::class);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calls');
    }
};
