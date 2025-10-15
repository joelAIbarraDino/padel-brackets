<script setup lang="ts">

import { TableRecordButton, TableActions, TableRecords } from '@/components/tableRecords';
import { Tabs,TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs"
import { BreadcrumbItem, AppPageProps, Place} from "@/types";
import { TableCell, TableRow } from "@/components/ui/table";
import { Head, usePage, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import Button from '@/components/ui/button/Button.vue';
import { Eye, ArrowLeft } from "lucide-vue-next";
import {computed, onMounted, ref} from 'vue';
import { TournamentBracket } from "vue3-tournament"
import type IRound from "vue3-tournament/interface/IRound"
import "vue3-tournament/style.css"


const breadcrumbs: BreadcrumbItem[] = [
    {title:'Torneos', href:'/tournaments'},
    {title:'Lugares del torneo', href:'#'}
]

const columnsName = [
    "Jugador", 
    "Estatus", 
    "Acciones"
];

interface TournamentPageProps extends AppPageProps{
    places: Place[]
}

const {props} = usePage<TournamentPageProps>();
const places = computed(() => props.places);

const onMatchClick = (matchId: string | number): void => {
  alert(`click: ${matchId}`)
}

const onParticipantClick = (participant: any, match: any): void => {
  console.log("participant", participant) // team or feedIn
  console.log("match", match)
}

const rounds = ref<IRound[]>([
  //Quarter
  {
    matchs: [
      {
        id: "match1",
        winner: "1",
        team1: { id: "1", name: "Competitor 1", score: 0 },
        team2: { id: "2", name: "Competitor 2", score: 0 },
      },
      {
        id: "match2",
        winner: "4",
        team1: { id: "3", name: "Competitor 3", score: 0 },
        team2: { id: "4", name: "Competitor 4", score: 0 },
      },
      {
        id: "match3",
        winner: "5",
        team1: { id: "5", name: "Competitor 5", score: 0 },
        team2: { id: "6", name: "Competitor 6", score: 0 },
      },
      {
        id: "match4",
        winner: "8",
        team1: { id: "7", name: "Competitor 7", score: 0 },
        team2: { id: "8", name: "Competitor 8", score: 2 },
      },
    ],
  },
  //Semi
  {
    matchs: [
      {
        id: "match5",
        winner: undefined,
        team1: { id: "1", name: "Pendiente", score: 0 },
        team2: { id: "4", name: "Pendiente", score: 0 },
      },
      {
        id: "match6",
        winner: undefined,
        team1: { id: "1", name: "Pendiente", score: 0 },
        team2: { id: "4", name: "Pendiente", score: 0 },
      },
    ],
  },
  //Final
  {
    matchs: [
      {
        id: "any_match_id",
        winner: undefined,
        team1: { id: "1", name: "Pendiente", score: 0 },
        team2: { id: "4", name: "Pendiente", score: 0 },
      },
    ],
  },
])

</script>

<template>
    <Head title="Lugares de torneo"/>
    <AppLayout :breadcrumbs="breadcrumbs">

            <div class="flex pl-4 pt-4">
                <Button as-child class="bg-lime-600 text-white hover:bg-lime-700">
                    <Link href="/tournaments"><ArrowLeft/>Regresar</Link>
                </Button>
            </div>

            <Tabs defaultValue="grafic" class="p-4">
                <TabsList>
                    <TabsTrigger value="grafic">Gráfica</TabsTrigger>
                    <TabsTrigger value="table">Tabla</TabsTrigger>
                </TabsList>

                <TabsContent value="grafic">
                    <div class="flex items-center justify-center">
                        <TournamentBracket
                            :rounds="rounds"
                            @onMatchClick="onMatchClick"
                            @onParticipantClick="onParticipantClick"   
                        />
                    </div>
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
                                    :icon=Eye
                                    :action="`#`"
                                />
                            </TableActions>
                        </TableRow>
                    </TableRecords>
                </TabsContent>
            </Tabs>
    </AppLayout>

</template>