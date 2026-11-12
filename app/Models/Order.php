<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Modelo Eloquent para las órdenes / comandas de clientes.
 */
class Order extends Model
{
    use HasFactory;

    public const STATUS_PENDIENTE = 'pendiente';
    public const STATUS_EN_PREPARACION = 'en_preparacion';
    public const STATUS_SERVIDO = 'servido';
    public const STATUS_PAGADO = 'pagado';

    public const STATUSES = [
        self::STATUS_PENDIENTE,
        self::STATUS_EN_PREPARACION,
        self::STATUS_SERVIDO,
        self::STATUS_PAGADO,
    ];

    /**
     * Atributos asignables en masa.
     *
     * @var list<string>
     */
    protected $fillable = [
        'table_id',
        'user_id',
        'total',
        'status',
        'tip_amount',
        'payment_method',
    ];

    /**
     * Definición de conversión de tipos de atributos.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'total' => 'decimal:2',
            'tip_amount' => 'decimal:2',
        ];
    }

    /**
     * Relación: Mesa en la que se originó la comanda.
     */
    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    /**
     * Relación: Mesero o usuario responsable de la comanda.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Ítems detallados dentro de la comanda.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relación: Comprobante o factura generada para esta comanda.
     */
    public function receipt(): HasOne
    {
        return $this->hasOne(Receipt::class);
    }
}

