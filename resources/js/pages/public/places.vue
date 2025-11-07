<script setup lang="ts">

import { AppPageProps, Place, Tournament } from '@/types';
import { usePage, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { HomePageName, PublicAppLayout } from '@/components/home';
import {TournamentBracket} from '@/components/tournamentBracket';
import Button from '@/components/ui/button/Button.vue';

interface TournamentPageProps extends AppPageProps{
    places: Place[],
    tournament: Tournament
}

const {props} = usePage<TournamentPageProps>();
const places = computed(() => props.places);
const tournament = computed(() => props.tournament);
let winners = null;

if(tournament.value.status === "finalizado"){
    winners = JSON.parse(tournament.value.result);
}


</script>

<template>
    <Head title="Torneos"/>

    <PublicAppLayout>
        <HomePageName title="Lugares del torneo"/>
        
        <TournamentBracket
            v-if="tournament.status==='activo'"
            :places="places" 
            base-url="/checkout/tournament/"
        />
        <div v-else class="p-5">
            <h2 class="text-6xl text-secondary font-bold text-center">Torneo finalizado</h2>

            <div class="w-5/10 bg-secondary p-5 mx-auto mt-10">
                <p class="text-primary text-center text-3xl font-bold">Resultados</p>

                <div class="bg-primary">
                    <div v-for="winner in winners":key="winner.id" class="flex items-center justify-center gap-5 text-white p-2 border-b last:border-b-0 first:mt-5">
                        <p class="text-yellow-400 text-2xl font-bold"># {{ winner.place }}</p>
                        <p class=" text-3xl font-bold">{{ winner.name }}</p>
                    </div>
                </div>

                
            </div>
            <div class="flex items-center justify-center mt-10">
                <Link href="/torneos" class="bg-primary p-4 text-secondary font-bold text-center hover:bg-secondary hover:text-primary transition text-xl">Ver más torneos</Link>
            </div>
        </div>
    </PublicAppLayout>
</template>