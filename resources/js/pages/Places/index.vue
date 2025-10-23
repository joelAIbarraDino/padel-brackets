<script setup lang="ts">

import { TableRecordButton, TableActions, TableRecords } from '@/components/tableRecords';
import { Tabs,TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs"
import { BreadcrumbItem, AppPageProps, Place} from "@/types";
import { TableCell, TableRow } from "@/components/ui/table";
import { Head, usePage, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import Button from '@/components/ui/button/Button.vue';
import { Eye, ArrowLeft } from "lucide-vue-next";
import {computed } from 'vue';
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
                  <div class="relative w-full overflow-x-auto bg-slate-100 p-6 rounded-lg">
                    
                    <div class="relative min-h-[600px]">
                      
                      <div
                        v-for="(round, roundIndex) in rounds"
                        :key="'round-' + roundIndex"
                        class="absolute top-0"
                        :style="{
                          left: `${roundIndex * 250}px`,
                          width: '200px'
                        }"
                      >
                        
                        <div class="flex flex-col items-center justify-between h-full">
                          <div
                            v-for="(match, matchIndex) in round"
                            :key="'match-' + matchIndex"
                            class="relative flex flex-col items-center"
                            :style="{
                              marginTop: `${match.spacing / 2}px`,
                              marginBottom: `${match.spacing / 2}px`,
                            }"
                          >
                            
                            <div class="w-40 text-center py-2 my-1 bg-sky-700 hover:bg-sky-600 text-white rounded cursor-pointer transition-all">
                              {{ match.player1?.user?.name ?? 'Sin jugador' }}
                            </div>
                            
                            <div class="w-40 text-center py-2 my-1 bg-sky-700 hover:bg-sky-600 text-white rounded cursor-pointer transition-all">
                              {{ match.player2?.user?.name ?? 'Sin jugador' }}
                            </div>
                          </div>
                        </div>

                      </div>

                    </div>

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