<script setup lang="ts">

import { TableRecordButton, TableActions, TableRecords } from '@/components/tableRecords';
import { Tabs,TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs"
import {TournamentBracket} from '@/components/tournamentBracket';
import { BreadcrumbItem, AppPageProps, Place} from "@/types";
import { TableCell, TableRow } from "@/components/ui/table";
import { Head, usePage, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import Button from '@/components/ui/button/Button.vue';
import { CalendarSync, ArrowLeft, Trophy } from "lucide-vue-next";
import {computed } from 'vue';
import "vue3-tournament/style.css"


const breadcrumbs: BreadcrumbItem[] = [
    {title:'Torneos', href:'/tournaments'},
    {title:'Lugares del torneo', href:'#'}
]

const columnsName = [
    "Jugador", 
    "Estatus"
];

interface TournamentPageProps extends AppPageProps{
    places: Place[],
    tournamentID:number
}

const {props} = usePage<TournamentPageProps>();
const places = computed(() => props.places);
const tournamentID = computed(()=>props.tournamentID);

const rounds = computed(() => {
  const totalPlayers = places.value.length;
  if (!totalPlayers) return [];

  const roundsArr: any[] = [];

  // Primera ronda
  const firstRound = [];
  for (let i = 0; i < totalPlayers; i += 2) {
    firstRound.push({
      player1: places.value[i],
      player2: places.value[i + 1],
      spacing: 40,
    });
  }
  roundsArr.push(firstRound);

  // Generar las siguientes rondas visualmente (sin datos aún)
  let matches = firstRound.length;
  let spacing = 80;
  while (matches > 1) {
    matches = Math.ceil(matches / 2);
    const round = Array.from({ length: matches }).map(() => ({
      player1: null,
      player2: null,
      spacing,
    }));
    roundsArr.push(round);
    spacing *= 2; // aumenta el espacio entre partidos visualmente
  }

  return roundsArr;
});
</script>

<template>
    <Head title="Lugares de torneo"/>
    <AppLayout :breadcrumbs="breadcrumbs">

            <div class="flex items-start flex-col md:flex-row md:items-center gap-5 pt-4 px-4">
                <Button as-child class="bg-lime-600 text-white hover:bg-lime-700">
                    <Link href="/tournaments"><ArrowLeft/>Regresar</Link>
                </Button>

                <Button as-child class="bg-yellow-500 text-white hover:bg-yellow-400">
                    <Link :href="`/tournaments/${tournamentID}/winner`"><Trophy/>Registrar ganador</Link>
                </Button>
            </div>

            <Tabs defaultValue="grafic" class="p-4">
                <TabsList>
                    <TabsTrigger value="grafic">Gráfica</TabsTrigger>
                    <TabsTrigger value="table">Tabla</TabsTrigger>
                </TabsList>

                <TabsContent value="grafic">
                  <TournamentBracket
                      :places="places" 
                      base-url="#"
                  />
                </TabsContent>

                <TabsContent value="table">
                    <TableRecords caption="Lista de lugares del torneo" :columnsHead="columnsName" class="pb-5">
                        <TableRow v-for="place in places":key="place.id">
                            <TableCell>{{ place.user?.name || 'Sin jugador' }}</TableCell>
                            <TableCell>{{ place.status }}</TableCell>

                            <TableActions>
                                <TableRecordButton
                                    type="url"
                                    color="bg-violet-700"
                                    hover="bg-violet-600"
                                    :icon=CalendarSync
                                    :action="`/places/${place.id}/reschedule`"
                                />
                            </TableActions>
                        </TableRow>
                    </TableRecords>
                </TabsContent>
            </Tabs>
    </AppLayout>

</template>