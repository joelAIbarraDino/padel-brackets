<script setup lang="ts">
import { dashboard, login, register } from '@/routes';
import { AppPageProps, Place } from '@/types';
import { usePage, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import Button from '@/components/ui/button/Button.vue';
import { HomePageName, PublicAppLayout } from '@/components/home';

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

const matchColor = (match: any) => {
  const p1 = !!match.player1?.user;
  const p2 = !!match.player2?.user;
  const count = (p1 ? 1 : 0) + (p2 ? 1 : 0);

  if (count === 0) return 'bg-green-700 hover:bg-green-600';
  if (count === 1) return 'bg-yellow-600 hover:bg-yellow-500';
  return 'bg-red-700 hover:bg-red-600';
};
</script>

<template>
    <Head title="Torneos"/>

    <PublicAppLayout>
        <main>
            <HomePageName title="Lugares del torneo"/>
            <div class="min-h-[700px] relative bg-neutral-200">
                    
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
                            <!-- Jugador 1 -->
                            <div
                            :class="[
                                'w-40 text-center py-2 my-1 text-white rounded cursor-pointer transition-all',
                                matchColor(match)
                            ]"
                            >
                            {{ match.player1?.user?.name ?? 'Sin jugador' }}
                            </div>

                            <!-- Jugador 2 -->
                            <div
                            :class="[
                                'w-40 text-center py-2 my-1 text-white rounded cursor-pointer transition-all',
                                matchColor(match)
                            ]"
                            >
                            {{ match.player2?.user?.name ?? 'Sin jugador' }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </PublicAppLayout>
</template>