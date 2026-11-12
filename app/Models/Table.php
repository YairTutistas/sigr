<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo Eloquent para las mesas físicas del restaurante.
 */
class Table extends Model
{
    use HasFactory;

    public const STATUS_DISPONIBLE = 'disponible';
    public const STATUS_OCUPADA = 'ocupada';
    public const STATUS_RESERVADA = 'reservada';

    public const STATUSES = [
        self::STATUS_DISPONIBLE,
        self::STATUS_OCUPADA,
        self::STATUS_RESERVADA,
    ];

    /**
     * Nombre de la tabla asociada en base de datos.
     *
     * @var string
     */
    protected $table = 'tables';

    /**
     * Atributos asignables en masa.
     *
     * @var list<string>
     */
    protected $fillable = [
        'number',
        'capacity',
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
            'number' => 'integer',
            'capacity' => 'integer',
        ];
    }

    /**
     * Relación: Reservaciones asociadas a esta mesa.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Relación: Órdenes / comandas registradas en esta mesa.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}

