<script setup lang="ts">
import { dashboard, login, register } from '@/routes';
import { AppPageProps, Place } from '@/types';
import { usePage, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import Button from '@/components/ui/button/Button.vue';

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

    <div class="bg-[#FDFDFC] p-6 text-[#1b1b18] lg:p-8 dark:bg-[#0a0a0a]">
        <header class="mb-6 w-full text-sm not-has-[nav]:hidden">
            <nav class="flex items-end justify-end gap-4">
                <Link
                    v-if="$page.props.auth.user"
                    :href="dashboard()"
                    class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                >
                    Dashboard
                </Link>
                <template v-else>
                    <Link
                        :href="login()"
                        class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                    >
                        Iniciar sesión
                    </Link>

                    <Link
                        :href="'/torneos'"
                        class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                    >
                        Calendario
                    </Link>

                    <Link
                        :href="register()"
                        class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                    >
                        Registrarse
                    </Link>
                </template>
            </nav>
        </header>

        <main>
            <div class="flex gap-3 items-center justify-start mb-2">
                <Button>
                    <Link href="/torneos">Regresar</Link>
                </Button>

                <h1 class="text-center font-bold text-2xl text-cyan-800 ">Lugares disponibles</h1>
            </div>
            <div class="relative w-full h-full overflow-x-auto bg-slate-100 p-6 rounded-lg">
                    
                <div class="relative min-h-[700px]">
                    
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

                </div>
        </main>

    </div>

</template>