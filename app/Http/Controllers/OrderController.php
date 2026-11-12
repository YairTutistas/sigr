<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Order;
use App\Models\Table;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Controlador para la gestión de órdenes / comandas y su flujo de estados.
 */
class OrderController extends Controller
{
    /**
     * Lista las comandas activas e históricas con sus relaciones.
     */
    public function index(Request $request): Response
    {
        $orders = Order::query()
            ->with(['table:id,number,status', 'user:id,name', 'items.dish:id,name,price', 'receipt'])
            ->when(
                $request->filled('status'),
                fn ($query) => $query->where('status', $request->string('status')->toString())
            )
            ->latest()
            ->get();

        $tables = Table::query()->orderBy('number')->get();
        $dishes = Dish::query()->where('is_available', true)->orderBy('name')->get();

        return Inertia::render('Orders/Index', [
            'orders' => $orders,
            'tables' => $tables,
            'dishes' => $dishes,
        ]);
    }

    /**
     * Crea una nueva comanda, calcula los subtotales y marca la mesa como ocupada.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'table_id' => ['required', 'integer', Rule::exists('tables', 'id')],
            'items' => ['required', 'array', 'min:1'],
            'items.*.dish_id' => ['required', 'integer', Rule::exists('dishes', 'id')],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.notes' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($validated, $request): void {
            $order = Order::create([
                'table_id' => $validated['table_id'],
                'user_id' => $request->user()->id,
                'total' => 0,
                'status' => Order::STATUS_PENDIENTE,
                'tip_amount' => 0,
            ]);

            $totalOrder = 0.0;

            foreach ($validated['items'] as $itemData) {
                /** @var Dish $dish */
                $dish = Dish::findOrFail($itemData['dish_id']);
                $unitPrice = (float) $dish->price;
                $quantity = (int) $itemData['quantity'];
                $subtotal = round($unitPrice * $quantity, 2);

                $order->items()->create([
                    'dish_id' => $dish->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                    'notes' => $itemData['notes'] ?? null,
                ]);

                $totalOrder += $subtotal;
            }

            $order->update(['total' => round($totalOrder, 2)]);

            Table::where('id', $validated['table_id'])->update([
                'status' => Table::STATUS_OCUPADA,
            ]);
        });

        return back()->with('success', 'Comanda creada y enviada a cocina exitosamente.');
    }

    /**
     * Actualiza el estado operativo del pedido (pendiente -> en_preparacion -> servido -> pagado).
     */
    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', Rule::in(Order::STATUSES)],
            'payment_method' => ['nullable', 'string', 'max:50'],
        ]);

        DB::transaction(function () use ($order, $validated): void {
            $order->update([
                'status' => $validated['status'],
                'payment_method' => $validated['payment_method'] ?? $order->payment_method,
            ]);

            if ($validated['status'] === Order::STATUS_PAGADO) {
                $order->table()->update(['status' => Table::STATUS_DISPONIBLE]);
            }
        });

        return back()->with('success', "Estado de la comanda #{$order->id} actualizado a {$validated['status']}.");
    }
}

