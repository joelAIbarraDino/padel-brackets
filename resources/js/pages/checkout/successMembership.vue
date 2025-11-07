<script setup lang="ts">
import { HomePageName, PublicAppLayout } from '@/components/home';
import { Button } from '@/components/ui/button';
import { AppPageProps, Membership } from '@/types';
import { usePage, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

//proms para los detalles de la compra
interface CheckoutPageProps extends AppPageProps{
    membership:Membership;
}

//proms del backend
const {props} = usePage<CheckoutPageProps>();
const membership = computed(() => props.membership);

</script>

<template>
    <PublicAppLayout>
        <HomePageName title="Pago exitoso"/>

        <div class="flex flex-col items-center justify-center min-h-[70vh] text-center px-6">
            <div class="max-w-md">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="mx-auto mb-6 h-20 w-20 text-green-500"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9 12l2 2l4-4m5 2a9 9 0 11-18 0a9 9 0 0118 0z"
                    />
                </svg>

                <p class="text-gray-600 mb-6"> Tu pago ha sido procesado correctamente. Ya aseguraste tu membresía en Lomas padel</p>

                <div class="bg-gray-50 rounded-2xl p-4 mb-6 text-sm text-gray-700 border border-gray-200">
                <p><strong>Nivel de membresía:</strong> {{ membership.name ?? '' }}</p>
                <p><strong>Precio:</strong> ${{ membership?.price ?? '' }} MXN</p>
                </div>

                
                <Button 
                    class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-xl shadow hover:bg-indigo-500 transition"
                >
                    <Link href="/">Volver al incio</Link>
                </Button>
            </div>
            </div>

    </PublicAppLayout>

</template>