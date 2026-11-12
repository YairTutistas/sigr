<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\CashRegister;
use App\Models\Order;
use App\Models\Receipt;
use App\Models\Table;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Controlador para la generación de comprobantes de pago y cálculo de propina voluntaria.
 */
class ReceiptController extends Controller
{
    /**
     * Porcentaje de impuesto al consumo / IVA por defecto (8%).
     */
    public const DEFAULT_TAX_RATE = 0.08;

    /**
     * Muestra el listado de comprobantes emitidos.
     */
    public function index(): Response
    {
        $receipts = Receipt::query()
            ->with(['order.table:id,number', 'order.user:id,name'])
            ->latest('issued_at')
            ->get();

        return Inertia::render('Receipts/Index', [
            'receipts' => $receipts,
        ]);
    }

    /**
     * Muestra el detalle de un comprobante específico.
     */
    public function show(Receipt $receipt): Response
    {
        $receipt->load(['order.items.dish', 'order.table', 'order.user']);

        return Inertia::render('Receipts/Show', [
            'receipt' => $receipt,
        ]);
    }

    /**
     * Genera el comprobante de pago de una orden, calculando impuestos y propina voluntaria.
     */
    public function generate(Request $request, Order $order): RedirectResponse
    {
        if ($order->receipt()->exists()) {
            return back()->withErrors([
                'receipt' => 'Esta comanda ya cuenta con un comprobante emitido.',
            ]);
        }

        $validated = $request->validate([
            'tip_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'tip_amount' => ['nullable', 'numeric', 'min:0'],
            'payment_method' => ['required', 'string', 'max:50'],
            'tax_rate' => ['nullable', 'numeric', 'min:0', 'max:1'],
        ]);

        DB::transaction(function () use ($order, $validated): void {
            $subtotal = (float) $order->items()->sum('subtotal');
            $taxRate = isset($validated['tax_rate']) ? (float) $validated['tax_rate'] : self::DEFAULT_TAX_RATE;
            $tax = round($subtotal * $taxRate, 2);

            // Cálculo de propina voluntaria (por monto fijo o porcentaje sugerido, ej. 10%)
            if (isset($validated['tip_amount'])) {
                $tipAmount = round((float) $validated['tip_amount'], 2);
            } elseif (isset($validated['tip_percentage'])) {
                $tipAmount = round($subtotal * ((float) $validated['tip_percentage'] / 100), 2);
            } else {
                $tipAmount = 0.00;
            }

            $total = round($subtotal + $tax + $tipAmount, 2);
            $invoiceNumber = sprintf('FAC-%s-%06d', now()->format('Ymd'), $order->id);

            Receipt::create([
                'order_id' => $order->id,
                'invoice_number' => $invoiceNumber,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'tip_amount' => $tipAmount,
                'total' => $total,
                'issued_at' => now(),
            ]);

            $order->update([
                'tip_amount' => $tipAmount,
                'total' => $total,
                'status' => Order::STATUS_PAGADO,
                'payment_method' => $validated['payment_method'],
            ]);

            $order->table()->update([
                'status' => Table::STATUS_DISPONIBLE,
            ]);

            // Incrementar las ventas de la sesión de caja activa si existe
            $activeCashRegister = CashRegister::query()
                ->where('status', CashRegister::STATUS_ABIERTA)
                ->latest('opened_at')
                ->first();

            if ($activeCashRegister !== null) {
                $activeCashRegister->increment('total_sales', $total);
            }
        });

        return back()->with('success', 'Comprobante generado y pago registrado exitosamente.');
    }
}

