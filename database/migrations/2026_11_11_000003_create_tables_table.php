<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración para la tabla de mesas del restaurante.
     */
    public function up(): void
    {
        Schema::create('tables', function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('number')->unique();
            $table->unsignedTinyInteger('capacity')->default(4);
            $table->enum('status', ['disponible', 'ocupada', 'reservada'])
                ->default('disponible')
                ->index();
            $table->timestamps();
        });
    }

    /**
     * Revierte la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};

