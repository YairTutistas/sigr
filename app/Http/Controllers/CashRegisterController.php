<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\CashRegister;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Controlador para la apertura, arqueo y cierre de caja registradora.
 */
class CashRegisterController extends Controller
{
    /**
     * Muestra el estado de la caja actual y el historial de sesiones.
     */
    public function index(): Response
    {
        $activeSession = CashRegister::query()
            ->with('user:id,name')
            ->where('status', CashRegister::STATUS_ABIERTA)
            ->latest('opened_at')
            ->first();

        $sessions = CashRegister::query()
            ->with('user:id,name')
            ->latest('opened_at')
            ->limit(25)
            ->get();

        return Inertia::render('CashRegister/Index', [
            'activeSession' => $activeSession,
            'sessions' => $sessions,
        ]);
    }

    /**
     * Realiza la apertura de una nueva sesión de caja.
     */
    public function open(Request $request): RedirectResponse
    {
        $hasOpenRegister = CashRegister::query()
            ->where('status', CashRegister::STATUS_ABIERTA)
            ->exists();

        if ($hasOpenRegister) {
            return back()->withErrors([
                'cash_register' => 'Ya existe una sesión de caja abierta actualmente.',
            ]);
        }

        $validated = $request->validate([
            'opening_amount' => ['required', 'numeric', 'min:0'],
        ]);

        CashRegister::create([
            'user_id' => $request->user()->id,
            'opening_amount' => $validated['opening_amount'],
            'opened_at' => now(),
            'status' => CashRegister::STATUS_ABIERTA,
            'total_sales' => 0.00,
        ]);

        return back()->with('success', 'Caja abierta exitosamente.');
    }

    /**
     * Realiza el cierre y arqueo de una sesión de caja abierta.
     */
    public function close(Request $request, CashRegister $cashRegister): RedirectResponse
    {
        if ($cashRegister->status === CashRegister::STATUS_CERRADA) {
            return back()->withErrors([
                'cash_register' => 'Esta sesión de caja ya se encuentra cerrada.',
            ]);
        }

        $validated = $request->validate([
            'closing_amount' => ['required', 'numeric', 'min:0'],
        ]);

        $totalSales = (float) Order::query()
            ->where('status', Order::STATUS_PAGADO)
            ->whereBetween('updated_at', [$cashRegister->opened_at, now()])
            ->sum('total');

        $cashRegister->update([
            'closing_amount' => $validated['closing_amount'],
            'closed_at' => now(),
            'status' => CashRegister::STATUS_CERRADA,
            'total_sales' => round($totalSales, 2),
        ]);

        return back()->with('success', 'Caja cerrada y arqueada correctamente.');
    }
}

