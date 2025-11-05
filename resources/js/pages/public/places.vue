<script setup lang="ts">

import { AppPageProps, Place } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { HomePageName, PublicAppLayout } from '@/components/home';
import {TournamentBracket} from '@/components/tournamentBracket';

interface TournamentPageProps extends AppPageProps{
    places: Place[]
}

const {props} = usePage<TournamentPageProps>();
const places = computed(() => props.places);

</script>

<template>
    <Head title="Torneos"/>

    <PublicAppLayout>
        <HomePageName title="Lugares del torneo"/>
        
        <TournamentBracket
            :places="places" 
            base-url="/checkout/tournament/"
        />
    </PublicAppLayout>
</template>