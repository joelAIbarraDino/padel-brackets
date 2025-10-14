<script setup lang="ts">

import ButtonNewRegister from '@/components/ButtonNewRegister.vue';
import TableRecordButton from '@/components/tableRecords/TableRecordButton.vue';
import TableActions from '@/components/tableRecords/TableActions.vue';
import TableRecords from '@/components/tableRecords/TableRecords.vue';

import { Tournament, BreadcrumbItem, AppPageProps} from "@/types";
import { TableCell, TableRow } from "@/components/ui/table";
import { Head, usePage, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Pencil, Trash, Users} from "lucide-vue-next";
import Swal from 'sweetalert2';
import {computed} from 'vue';


const breadcrumbs: BreadcrumbItem[] = [{title:'Torneos', href:'/tournaments'}]

const columnsName = [
    "Tipo de torneo", 
    "Fecha de evento", 
    "Precio de admisión", 
    "Estado", 
    "Acciones"
];

interface TournamentPageProps extends AppPageProps{
    tournaments:Tournament[];
}

const {props} = usePage<TournamentPageProps>();
const tournaments = computed(() => props.tournaments);


//eliminar registro
const deleteTournament = async (id: number)=> {

    const result = await Swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás revertir esto!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    });

    if(!result.isConfirmed)return;

    router.delete(`/tournaments/${id}`,{
        preserveScroll:true,
        onSuccess:()=>{
            router.visit('/tournaments', {replace:true});
        },
        onError:(errors)=>{
            console.error("Error al eliminar el registro: ", errors);
            Swal.fire('Error', 'No se pudo eliminar el torneo.', 'error');
        }
    });

}

</script>

<template>
    <Head title="Torneos"/>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">

            <ButtonNewRegister url="/tournaments/create" text="Crear"/>
            
            <TableRecords caption="Lista de torneos" :columnsHead="columnsName">

                <TableRow v-for="tournament in tournaments" :Key="tournament.id">
                    <TableCell>{{tournament.type}}</TableCell>
                    <TableCell>{{tournament.scheduled_event}}</TableCell>
                    <TableCell>$ {{tournament.admission_price}}</TableCell>
                    <TableCell>{{tournament.status}}</TableCell>
                    
                    <TableActions>
                        <TableRecordButton
                            type="url"
                            color="bg-cyan-600"
                            hover="bg-cyan-700"
                            :icon=Pencil
                            :action="`/tournaments/${tournament.id}/edit`"
                        />

                        <TableRecordButton
                            type="function"
                            color="bg-red-700"
                            hover="bg-red-600"
                            :icon=Trash
                            :action="() => deleteTournament(tournament.id)"
                        />

                        <TableRecordButton
                            type="url"
                            color="bg-violet-700"
                            hover="bg-violet-600"
                            :icon=Trash
                            :action="`#`"
                        />

                    </TableActions>    

                </TableRow>

            </TableRecords>
        </div>
    </AppLayout>
</template>