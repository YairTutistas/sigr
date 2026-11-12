<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo Eloquent para los comprobantes de pago y facturación.
 */
class Receipt extends Model
{
    use HasFactory;

    /**
     * Atributos asignables en masa.
     *
     * @var list<string>
     */
    protected $fillable = [
        'order_id',
        'invoice_number',
        'subtotal',
        'tax',
        'tip_amount',
        'total',
        'issued_at',
    ];

    /**
     * Definición de conversión de tipos de atributos.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'tax' => 'decimal:2',
            'tip_amount' => 'decimal:2',
            'total' => 'decimal:2',
            'issued_at' => 'datetime',
        ];
    }

    /**
     * Relación: Orden pagada asociada al comprobante.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}

