<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo Eloquent para el detalle de platos incluidos en una comanda.
 */
class OrderItem extends Model
{
    use HasFactory;

    /**
     * Atributos asignables en masa.
     *
     * @var list<string>
     */
    protected $fillable = [
        'order_id',
        'dish_id',
        'quantity',
        'unit_price',
        'subtotal',
        'notes',
    ];

    /**
     * Definición de conversión de tipos de atributos.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'unit_price' => 'decimal:2',
            'subtotal' => 'decimal:2',
        ];
    }

    /**
     * Relación: Comanda principal a la que pertenece el ítem.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relación: Plato solicitado en este ítem.
     */
    public function dish(): BelongsTo
    {
        return $this->belongsTo(Dish::class);
    }
}

