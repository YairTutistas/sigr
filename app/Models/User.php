<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Modelo Eloquent para los usuarios del sistema SIGR.
 */
class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_MESERO = 'mesero';
    public const ROLE_CAJERO = 'cajero';
    public const ROLE_COCINA = 'cocina';
    public const ROLE_CLIENTE = 'cliente';

    public const ROLES = [
        self::ROLE_ADMIN,
        self::ROLE_MESERO,
        self::ROLE_CAJERO,
        self::ROLE_COCINA,
        self::ROLE_CLIENTE,
    ];

    /**
     * Atributos asignables en masa.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * Atributos ocultos para la serialización.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Definición de conversión de tipos de atributos.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relación: Órdenes (comandas) atendidas por el usuario.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Relación: Sesiones de caja aperturadas por el usuario.
     */
    public function cashRegisters(): HasMany
    {
        return $this->hasMany(CashRegister::class);
    }

    /**
     * Verifica si el usuario posee un rol específico o alguno de una lista.
     *
     * @param string|list<string> $roles
     */
    public function hasRole(string|array $roles): bool
    {
        if (is_array($roles)) {
            return in_array($this->role, $roles, true);
        }

        return $this->role === $roles;
    }
}

