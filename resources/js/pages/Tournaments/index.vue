<script setup lang="ts">

import { TableRecordButton, TableActions, TableRecords } from '@/components/tableRecords';
import { Tabs,TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs"
import ButtonNewRegister from '@/components/ButtonNewRegister.vue';
import { Tournament, BreadcrumbItem, AppPageProps} from "@/types";
import { TableCell, TableRow } from "@/components/ui/table";
import { Pencil, Trash, Network, Trophy} from "lucide-vue-next";
import { Head, usePage, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import Swal from 'sweetalert2';
import {computed, ref} from 'vue';

import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'
import { EventClickArg } from '@fullcalendar/core/index.js';

function formatDateTime(dateString:string) {
  if (!dateString) return 'No definida'

  const date = new Date(dateString)

  return date.toLocaleString('es-MX', {
    day: '2-digit',
    month: 'long',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
    hour12: true,
  })
}

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
    tournamentCalendar:[]
}

const {props} = usePage<TournamentPageProps>();
const tournaments = computed(() => props.tournaments);
const tournamentsCalendar = computed(()=> props.tournamentCalendar);

const calendarOptions = ref({
  plugins: [dayGridPlugin, interactionPlugin],
  initialView: 'dayGridMonth',
  events: tournamentsCalendar,
  eventClick: function(info:EventClickArg) {
    // Redirige al hacer click
    window.location.href = info.event.url
    info.jsEvent.preventDefault()
  }
})


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
            
            <Tabs defaultValue="calendar" class="p-4">
                <TabsList>
                    <TabsTrigger value="calendar">Calendario</TabsTrigger>
                    <TabsTrigger value="table">Tabla</TabsTrigger>
                </TabsList>

                <TabsContent value="calendar">
                    <FullCalendar :options="calendarOptions" class="mt-5 w-7/10 mx-auto"/>
                </TabsContent>

                <TabsContent value="table">
                   <TableRecords caption="Lista de torneos" :columnsHead="columnsName" class="pb-5">
                        <TableRow v-for="tournament in tournaments" :Key="tournament.id">
                            <TableCell>{{tournament.type_tournament?.name || 'Sin tipo'}}</TableCell>
                            <TableCell>{{formatDateTime(tournament.scheduled_event)}}</TableCell>
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
                                    :icon=Network
                                    :action="`/places/${tournament.id}`"
                                />

                                <TableRecordButton
                                    type="url"
                                    color="bg-yellow-500"
                                    hover="bg-yellow-400"
                                    :icon=Trophy
                                    :action="`/tournaments/${tournament.id}/winner`"
                                />

                            </TableActions>    

                        </TableRow>

                    </TableRecords> 
                </TabsContent>
            </Tabs>
        </div>
    </AppLayout>
</template>