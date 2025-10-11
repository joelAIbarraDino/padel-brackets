<script setup lang="ts">
import ButtonNewRegister from '@/components/ButtonNewRegister.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { AppPageProps, BreadcrumbItem, TypeTournament } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

//breadcrumbs de la página
const breadcrumbs: BreadcrumbItem[] = [{title:'Tipo de torneos', href:'/type-tournaments'}]

//interfaz para recuperar los tipos de torneos que nos da PHP
interface TypeTournamentsPageProps extends AppPageProps{
    typeTournaments: TypeTournament[];
}

//convertimos a objeto para el renderizado
const {props} = usePage<TypeTournamentsPageProps>();
const typeTournaments = computed(()=>props.typeTournaments);


//funcion para eliminar un registro

</script>

<template>
    <Head title="Tipos de torneo"/>

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col-gap-4 rounded-xl p-4">
            <ButtonNewRegister url="/type-tournaments/create" text="Crear"></ButtonNewRegister>
        </div>
    </AppLayout>

</template>