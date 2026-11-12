<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Controlador para el registro de reservaciones y verificación de disponibilidad de mesas.
 */
class ReservationController extends Controller
{
    /**
     * Muestra el listado de reservaciones y mesas del restaurante.
     */
    public function index(): Response
    {
        $reservations = Reservation::query()
            ->with('table:id,number,capacity,status')
            ->orderBy('reservation_date')
            ->get();

        $tables = Table::query()->orderBy('number')->get();

        return Inertia::render('Reservations/Index', [
            'reservations' => $reservations,
            'tables' => $tables,
        ]);
    }

    /**
     * Verifica la disponibilidad de mesas para una fecha, hora y cantidad de comensales.
     */
    public function checkAvailability(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'reservation_date' => ['required', 'date'],
            'guests' => ['required', 'integer', 'min:1'],
        ]);

        $targetTime = Carbon::parse($validated['reservation_date']);
        $windowStart = $targetTime->copy()->subHours(2);
        $windowEnd = $targetTime->copy()->addHours(2);

        $reservedTableIds = Reservation::query()
            ->whereIn('status', [Reservation::STATUS_PENDIENTE, Reservation::STATUS_CONFIRMADA])
            ->whereBetween('reservation_date', [$windowStart, $windowEnd])
            ->pluck('table_id');

        $availableTables = Table::query()
            ->where('capacity', '>=', (int) $validated['guests'])
            ->whereNotIn('id', $reservedTableIds)
            ->orderBy('capacity')
            ->orderBy('number')
            ->get();

        return response()->json([
            'available' => $availableTables->isNotEmpty(),
            'tables' => $availableTables,
        ]);
    }

    /**
     * Registra una nueva reservación validando cruces de horario en la mesa seleccionada.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:150'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'table_id' => ['required', 'integer', Rule::exists('tables', 'id')],
            'reservation_date' => ['required', 'date', 'after:now'],
        ]);

        $targetTime = Carbon::parse($validated['reservation_date']);
        $hasConflict = Reservation::query()
            ->where('table_id', $validated['table_id'])
            ->whereIn('status', [Reservation::STATUS_PENDIENTE, Reservation::STATUS_CONFIRMADA])
            ->whereBetween('reservation_date', [
                $targetTime->copy()->subHours(2),
                $targetTime->copy()->addHours(2),
            ])
            ->exists();

        if ($hasConflict) {
            return back()->withErrors([
                'table_id' => 'La mesa seleccionada ya posee una reservación activa en ese rango de horario.',
            ]);
        }

        DB::transaction(function () use ($validated): void {
            Reservation::create([
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'table_id' => $validated['table_id'],
                'reservation_date' => $validated['reservation_date'],
                'status' => Reservation::STATUS_CONFIRMADA,
            ]);

            Table::where('id', $validated['table_id'])
                ->where('status', Table::STATUS_DISPONIBLE)
                ->update(['status' => Table::STATUS_RESERVADA]);
        });

        return back()->with('success', 'Reservación registrada y confirmada exitosamente.');
    }

    /**
     * Actualiza el estado de una reservación (pendiente, confirmada, cancelada).
     */
    public function updateStatus(Request $request, Reservation $reservation): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', Rule::in(Reservation::STATUSES)],
        ]);

        DB::transaction(function () use ($reservation, $validated): void {
            $reservation->update(['status' => $validated['status']]);

            if ($validated['status'] === Reservation::STATUS_CANCELADA) {
                $reservation->table()->update(['status' => Table::STATUS_DISPONIBLE]);
            } elseif ($validated['status'] === Reservation::STATUS_CONFIRMADA) {
                $reservation->table()->update(['status' => Table::STATUS_RESERVADA]);
            }
        });

        return back()->with('success', 'Estado de la reservación actualizado correctamente.');
    }
}

