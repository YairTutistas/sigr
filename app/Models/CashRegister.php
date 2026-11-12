<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo Eloquent para sesiones de apertura y arqueo de caja.
 */
class CashRegister extends Model
{
    use HasFactory;

    public const STATUS_ABIERTA = 'abierta';
    public const STATUS_CERRADA = 'cerrada';

    public const STATUSES = [
        self::STATUS_ABIERTA,
        self::STATUS_CERRADA,
    ];

    /**
     * Atributos asignables en masa.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'opening_amount',
        'closing_amount',
        'opened_at',
        'closed_at',
        'status',
        'total_sales',
    ];

    /**
     * Definición de conversión de tipos de atributos.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'opening_amount' => 'decimal:2',
            'closing_amount' => 'decimal:2',
            'total_sales' => 'decimal:2',
            'opened_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    /**
     * Relación: Cajero o administrador que abrió la sesión de caja.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

