<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo Eloquent para la gestión de reservaciones de mesas.
 */
class Reservation extends Model
{
    use HasFactory;

    public const STATUS_PENDIENTE = 'pendiente';
    public const STATUS_CONFIRMADA = 'confirmada';
    public const STATUS_CANCELADA = 'cancelada';

    public const STATUSES = [
        self::STATUS_PENDIENTE,
        self::STATUS_CONFIRMADA,
        self::STATUS_CANCELADA,
    ];

    /**
     * Atributos asignables en masa.
     *
     * @var list<string>
     */
    protected $fillable = [
        'customer_name',
        'customer_phone',
        'table_id',
        'reservation_date',
        'status',
    ];

    /**
     * Definición de conversión de tipos de atributos.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'reservation_date' => 'datetime',
        ];
    }

    /**
     * Relación: Mesa asignada a la reservación.
     */
    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }
}

