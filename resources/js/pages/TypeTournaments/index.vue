<script setup lang="ts">
import ButtonNewRegister from '@/components/ButtonNewRegister.vue';

import { TableRecordButton, TableActions, TableRecords } from '@/components/tableRecords';

import { AppPageProps, BreadcrumbItem, TypeTournament } from '@/types';
import { TableCell, TableRow } from '@/components/ui/table';
import { Head, usePage, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Pencil, Trash} from "lucide-vue-next";
import { computed } from 'vue';

//breadcrumbs de la página
const breadcrumbs: BreadcrumbItem[] = [{title:'Tipo de torneos', href:'/type-tournaments'}]

const columnsName = ["Tipo de torneo", "Acciones"]

//interfaz para recuperar los tipos de torneos que nos da PHP
interface TypeTournamentsPageProps extends AppPageProps{
    typeTournaments:TypeTournament[];
}

//convertimos a objeto para el renderizado
const {props} = usePage<TypeTournamentsPageProps>();
const typeTournaments = computed(() => props.typeTournaments);

//funcion para eliminar un registro
const deleteTypeTournament = async(id:number)=>{

}


</script>

<template>
    <Head title="Tipos de torneo"/>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">

            <ButtonNewRegister url="/type-tournaments/create" text="Crear"/>

            <TableRecords caption="Lista de tipos de torneos" :columns-head="columnsName">
                <TableRow v-for="typeTournament in typeTournaments":key="typeTournament.id">
                    <TableCell>{{ typeTournament.name }}</TableCell>

                    <TableActions>
                        <TableRecordButton
                            type="url"
                            color="bg-cyan-600"
                            hover="bg-cyan-700"
                            :icon=Pencil
                            :action="`/type-tournaments/${typeTournament.id}/edit`"
                        />

                        <TableRecordButton
                            type="function"
                            color="bg-red-700"
                            hover="bg-red-600"
                            :icon=Trash
                            :action="() => deleteTypeTournament(typeTournament.id)"
                        />
                        
                    </TableActions>

                </TableRow>
            </TableRecords>
        </div>
    </AppLayout>

</template>