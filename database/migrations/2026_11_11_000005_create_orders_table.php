<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración para la tabla de órdenes / comandas.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('table_id')
                ->constrained('tables')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->decimal('total', 10, 2)->default(0.00);
            $table->enum('status', ['pendiente', 'en_preparacion', 'servido', 'pagado'])
                ->default('pendiente')
                ->index();
            $table->decimal('tip_amount', 10, 2)->default(0.00);
            $table->string('payment_method', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Revierte la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

