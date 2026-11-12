<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Dish;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Controlador para la gestión del menú (CRUD de platos y categorías).
 */
class MenuController extends Controller
{
    /**
     * Lista las categorías y los platos del menú.
     */
    public function index(Request $request): Response
    {
        $categories = Category::query()
            ->withCount('dishes')
            ->orderBy('name')
            ->get();

        $dishes = Dish::query()
            ->with('category:id,name,slug')
            ->when(
                $request->filled('category_id'),
                fn ($query) => $query->where('category_id', $request->integer('category_id'))
            )
            ->when(
                $request->filled('search'),
                fn ($query) => $query->where('name', 'like', '%' . $request->string('search')->toString() . '%')
            )
            ->orderBy('name')
            ->get();

        return Inertia::render('Menu/Index', [
            'categories' => $categories,
            'dishes' => $dishes,
            'filters' => $request->only(['category_id', 'search']),
        ]);
    }

    /**
     * Almacena una nueva categoría de menú.
     */
    public function storeCategory(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:120', Rule::unique('categories', 'slug')],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['boolean'],
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);

        Category::create($validated);

        return back()->with('success', 'Categoría creada correctamente.');
    }

    /**
     * Actualiza una categoría existente.
     */
    public function updateCategory(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:120', Rule::unique('categories', 'slug')->ignore($category->id)],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['boolean'],
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);

        $category->update($validated);

        return back()->with('success', 'Categoría actualizada correctamente.');
    }

    /**
     * Elimina una categoría si no tiene platos asociados.
     */
    public function destroyCategory(Category $category): RedirectResponse
    {
        if ($category->dishes()->exists()) {
            return back()->withErrors([
                'category' => 'No se puede eliminar una categoría que contiene platos asociados.',
            ]);
        }

        $category->delete();

        return back()->with('success', 'Categoría eliminada correctamente.');
    }

    /**
     * Almacena un nuevo plato / producto en el menú.
     */
    public function storeDish(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')],
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0'],
            'cost' => ['required', 'numeric', 'min:0'],
            'is_available' => ['boolean'],
            'image_url' => ['nullable', 'string', 'max:255'],
        ]);

        Dish::create($validated);

        return back()->with('success', 'Plato registrado correctamente.');
    }

    /**
     * Actualiza los datos de un plato existente.
     */
    public function updateDish(Request $request, Dish $dish): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')],
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0'],
            'cost' => ['required', 'numeric', 'min:0'],
            'is_available' => ['boolean'],
            'image_url' => ['nullable', 'string', 'max:255'],
        ]);

        $dish->update($validated);

        return back()->with('success', 'Plato actualizado correctamente.');
    }

    /**
     * Elimina un plato del menú.
     */
    public function destroyDish(Dish $dish): RedirectResponse
    {
        $dish->delete();

        return back()->with('success', 'Plato eliminado correctamente.');
    }
}

