<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth?.user);
</script>

<template>
    <Head title="Panel Principal" />

    <div class="min-h-screen bg-slate-50">
        <header class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-slate-900">SIGR — Panel Operativo</h1>
                <p class="text-xs text-slate-500">Línea Base v1.0.0 (LB-01)</p>
            </div>
            <div v-if="user" class="flex items-center gap-4">
                <span class="text-sm text-slate-700">
                    {{ user.name }} <strong class="uppercase text-xs bg-orange-100 text-orange-800 px-2 py-0.5 rounded">{{ user.role }}</strong>
                </span>
                <Link href="/logout" method="post" as="button" class="text-sm text-red-600 hover:underline">
                    Cerrar sesión
                </Link>
            </div>
        </header>

        <main class="max-w-7xl mx-auto p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <Link href="/menu" class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 hover:border-orange-500 transition">
                <h2 class="text-lg font-semibold text-slate-900">Menú y Categorías</h2>
                <p class="text-sm text-slate-500 mt-1">Administración de platos, costos, precios y disponibilidad.</p>
            </Link>

            <Link href="/orders" class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 hover:border-orange-500 transition">
                <h2 class="text-lg font-semibold text-slate-900">Comandas / Pedidos</h2>
                <p class="text-sm text-slate-500 mt-1">Toma de pedidos en mesa y seguimiento en cocina.</p>
            </Link>

            <Link href="/reservations" class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 hover:border-orange-500 transition">
                <h2 class="text-lg font-semibold text-slate-900">Reservaciones y Mesas</h2>
                <p class="text-sm text-slate-500 mt-1">Control de disponibilidad de mesas y reservas por horario.</p>
            </Link>

            <Link href="/cash-registers" class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 hover:border-orange-500 transition">
                <h2 class="text-lg font-semibold text-slate-900">Caja Registradora</h2>
                <p class="text-sm text-slate-500 mt-1">Apertura de turno, control de ingresos y cierre de caja.</p>
            </Link>

            <Link href="/receipts" class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 hover:border-orange-500 transition">
                <h2 class="text-lg font-semibold text-slate-900">Facturación y Comprobantes</h2>
                <p class="text-sm text-slate-500 mt-1">Emisión de facturas y cálculo de propina voluntaria.</p>
            </Link>
        </main>
    </div>
</template>

