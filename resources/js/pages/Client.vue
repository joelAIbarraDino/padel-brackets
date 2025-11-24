<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { player } from '@/routes';
import { AppPageProps, Place, Subscription, Tournament, type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Mi perfil',
        href: player.url(),
    },
];

interface PlayerPageProps extends AppPageProps{
    tournaments:Tournament[],
    subscription:Subscription,
}

const {props} = usePage<PlayerPageProps>();
const tournaments = computed(() => props.tournaments);
const subscription = computed(() => props.subscription);


</script>

<template>
    <Head title="Mi perfil" />

    <AppLayout :breadcrumbs="breadcrumbs">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-5">

            <!-- MEMBERSHIP CARD -->
            <div class="p-6 bg-white dark:bg-zinc-900 rounded-xl shadow border">
                <h2 class="text-xl font-semibold mb-2">Mi Membresía</h2>

                <div v-if="subscription && subscription.membership">
                    <p class="text-lg font-medium">
                        {{ subscription.membership.name }}
                    </p>

                    <p class="text-zinc-600 dark:text-zinc-400 text-sm mt-1">
                        Vence el:
                        <span class="font-semibold">
                            {{ new Date(subscription.expired_at??'').toLocaleDateString() }}
                        </span>
                    </p>

                    <p
                        class="mt-2 inline-block px-3 py-1 rounded-full text-white text-sm"
                        :class="new Date(subscription.expired_at??'') > new Date()
                            ? 'bg-green-600'
                            : 'bg-red-600'"
                    >
                        {{ new Date(subscription.expired_at??'') > new Date()
                            ? 'Activa'
                            : 'Vencida' }}
                    </p>
                </div>

                <div v-else class="text-zinc-500 dark:text-zinc-400">
                    No tienes una membresía activa.
                    <br>
                </div>
            </div>

            <!-- TOURNAMENT CARD -->
            <div class="p-6 bg-white dark:bg-zinc-900 rounded-xl shadow border">
                <h2 class="text-xl font-semibold mb-2">Mis Torneos</h2>

                <div v-if="tournaments">
                    <p class="text-lg font-medium mb-4">
                        Próximo torneo inscrito:
                    </p>

                    <div class="py-2 border-b last:border-b-0 " v-for="tournament in tournaments":key="tournament.id">
                        <div class="flex items-center justify-between">
                            <p class="text-zinc-700 dark:text-zinc-300 mt-1 text-lg">
                                {{ tournament.type_tournament?.name }}
                            </p>

                            <p class="text-md text-zinc-500 dark:text-zinc-400 mt-1">
                                Fecha: 
                                <span class="font-semibold">
                                    {{ new Date(tournament.scheduled_event).toLocaleString() }}
                                </span>
                            </p>
                        </div>                    

                        <a
                            :href="`/torneos/places/${tournament.id}`"
                            class="mt-2 inline-block text-blue-600 hover:underline text-sm"

                        >Ver detalles →</a>
                    </div>
                </div>

                <div v-else class="text-zinc-500 dark:text-zinc-400">
                    No estás inscrito en ningún torneo.
                    <br>
                    <a href="/torneos" class="text-blue-600 hover:underline">
                        Ver Torneos
                    </a>
                </div>
            </div>

        </div>

    </AppLayout>
</template>

