<script setup>
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Iniciar Sesión" />

    <div class="min-h-screen flex items-center justify-center bg-slate-900 px-4">
        <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-slate-900">SIGR v1.0.0</h1>
                <p class="text-sm text-slate-500 mt-1">Sistema Integral de Gestión de Restaurante</p>
            </div>

            <form @submit.prevent="submit" class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Correo Electrónico</label>
                    <input
                        v-model="form.email"
                        type="email"
                        required
                        autofocus
                        placeholder="admin@sigr.local"
                        class="w-full rounded-lg border-slate-300 focus:border-orange-600 focus:ring-orange-600"
                    />
                    <p v-if="form.errors.email" class="text-xs text-red-600 mt-1">{{ form.errors.email }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Contraseña</label>
                    <input
                        v-model="form.password"
                        type="password"
                        required
                        placeholder="••••••••"
                        class="w-full rounded-lg border-slate-300 focus:border-orange-600 focus:ring-orange-600"
                    />
                </div>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full py-2.5 px-4 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-lg transition"
                >
                    Ingresar al Sistema
                </button>
            </form>
        </div>
    </div>
</template>

