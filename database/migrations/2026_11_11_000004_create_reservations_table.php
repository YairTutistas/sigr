<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración para la tabla de reservaciones de mesas.
     */
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table): void {
            $table->id();
            $table->string('customer_name', 150);
            $table->string('customer_phone', 30);
            $table->foreignId('table_id')
                ->constrained('tables')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->dateTime('reservation_date')->index();
            $table->enum('status', ['pendiente', 'confirmada', 'cancelada'])
                ->default('pendiente')
                ->index();
            $table->timestamps();
        });
    }

    /**
     * Revierte la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};

