<script setup lang="ts">
import { ref, computed, onMounted, h, watch } from 'vue';
import { VueFlow, useVueFlow, Node, Edge, Position, Handle } from '@vue-flow/core';
import { Background } from '@vue-flow/background';
import { Link } from "@inertiajs/vue3";
import type { Place } from "@/types";

interface Props {
  places: Place[];
  baseUrl?: string;
}

const props = withDefaults(defineProps<Props>(), {
  baseUrl: '/checkout/tournament/'
});

const { fitView } = useVueFlow();

const nodes = ref<Node[]>([]);
const edges = ref<Edge[]>([]);

// Agrupar lugares en equipos (2 lugares por equipo)
const teams = computed(() => {
  const list = [];
  for (let i = 0; i < props.places.length; i += 2) {
    list.push(props.places.slice(i, i + 2));
  }
  return list;
});

const nodeWidth = 220;
const nodeHeight = 120;
const horizontalSpacing = 350;
const verticalSpacing = 20;

// color de equipo según ocupación
const getTeamNodeColor = (team: Place[]) => {
  const occupiedCount = team.filter(p => p.status === 'ocupado').length;
  if (occupiedCount === 2) return '#EF4444';
  if (occupiedCount === 1) return '#F59E0B';
  return '#10B981';
};

// Genera nodo-id helpers
const teamNodeId = (i:number) => `team-${i}`;
const matchNodeId = (round:number, i:number) => `match-${round}-${i}`;

// Genera todo el bracket (equipos + rondas)
const generateBrackets = () => {
  const newNodes: Node[] = [];
  const newEdges: Edge[] = [];

  const teamCount = teams.value.length;
  if (teamCount === 0) {
    nodes.value = [];
    edges.value = [];
    return;
  }

  // rounds: número de rondas de partidos (log2 del número de equipos), si no potencia de dos se usan ceil
  const rounds = Math.ceil(Math.log2(teamCount));

  const slotHeight = nodeHeight + verticalSpacing;

  // Ronda 0: nodos de equipos (posición x = 0)
  teams.value.forEach((team, idx) => {
    const x = 0;
    const y = idx * slotHeight;
    newNodes.push({
      id: teamNodeId(idx),
      type: 'team',
      position: { x, y },
      data: { team, color: getTeamNodeColor(team), baseUrl: props.baseUrl, index: idx },
      // conexiones por los lados: fuente a la derecha, target por la izquierda
      sourcePosition: Position.Right,
      targetPosition: Position.Left,
      style: { width: `${nodeWidth}px`, height: `${nodeHeight}px` }
    });
  });

  const nodeExists = (id: string) => newNodes.some(n => n.id === id);

  // Rondas de partidos (1..rounds)
  for (let r = 1; r <= rounds; r++) {
    const matchesThisRound = Math.ceil(teamCount / Math.pow(2, r));
    for (let m = 0; m < matchesThisRound; m++) {
      // Calcula posición vertical para espaciar acorde a la ronda
      const groupHeight = slotHeight * Math.pow(2, r - 1);
      const y = (m + 0.8) * groupHeight - nodeHeight / 2;
      const x = r * horizontalSpacing;

      newNodes.push({
        id: matchNodeId(r, m),
        type: 'match',
        position: { x, y },
        data: { round: r, index: m },
        // match recibe por la izquierda y output por la derecha hacia la siguiente ronda
        targetPosition: Position.Left,
        sourcePosition: Position.Right,
        style: { width: `${nodeWidth}px`, height: `${nodeHeight}px` }
      });

      const srcA = (r - 1 === 0) ? teamNodeId(m * 2) : matchNodeId(r - 1, m * 2);
      const srcB = (r - 1 === 0) ? teamNodeId(m * 2 + 1) : matchNodeId(r - 1, m * 2 + 1);

      // Crear aristas solo si el nodo fuente ya fue creado
      if (nodeExists(srcA)) {
        newEdges.push({
          id: `edge-${srcA}-${matchNodeId(r, m)}`,
          source: srcA,
          target: matchNodeId(r, m),
          type: 'smoothstep',
          animated: true,
          style: { stroke: '#6B7280', strokeWidth: 2 }
        });
      }

      if (nodeExists(srcB)) {
        newEdges.push({
          id: `edge-${srcB}-${matchNodeId(r, m)}`,
          source: srcB,
          target: matchNodeId(r, m),
          type: 'smoothstep',
          animated: true,
          style: { stroke: '#6B7280', strokeWidth: 2 }
        });
      }
    }
  }

  nodes.value = newNodes;
  edges.value = newEdges;
};

// Nodo visual para equipo
const TeamNode = (props: { data: any }) => {
  const { data } = props;
  const team: Place[] = data.team;
  return h(
    'div',
    {
      class: 'p-3 rounded-lg shadow-md border-2 text-white text-center',
      style: { backgroundColor: data.color, width: `${nodeWidth}px`, height: `${nodeHeight}px`, display: 'flex', flexDirection: 'column', justifyContent: 'center', position: 'relative' }
    },
    [
      // Source handle a la derecha
      h(Handle, { type: 'source', position: Position.Right }),
      h('div', { class: 'font-bold mb-2' }, `Equipo ${data.index + 1}`),
      h('div', { class: 'flex flex-col gap-2 justify-center' },
        team.map((place: Place, i: number) => {
          if (place.status === 'ocupado') {
            return h('span', { key: place.id, class: 'bg-white text-gray-800 px-2 py-1 rounded text-sm' }, place.user?.name ?? 'Usuario');
          }
          return h(
            Link,
            {
              key: place.id,
              href: `${data.baseUrl}${place.id}`,
              class: 'bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700 transition-colors text-sm'
            },
            `Lugar ${i + 1}`
          );
        })
      )
    ]
  );
};

// Nodo visual para partido
const MatchNode = (props: { data: any }) => {
  const { data } = props;
  return h(
    'div',
    {
      class: 'p-3 rounded-lg shadow-sm border-2 bg-gray-100 text-gray-800 text-center',
      style: { width: `${nodeWidth}px`, height: `${nodeHeight}px`, display: 'flex', flexDirection: 'column', justifyContent: 'center', position: 'relative' }
    },
    [
      // Target handle a la izquierda (entrada)
      h(Handle, { type: 'target', position: Position.Left }),
      h('div', { class: 'font-bold' }, `Partido`),
      h('div', { class: 'text-sm mt-1' }, `Ronda ${data.round}`),
      // Source handle a la derecha (salida hacia siguiente ronda)
      h(Handle, { type: 'source', position: Position.Right })
    ]
  );
};

const nodeTypes = {
  team: TeamNode,
  match: MatchNode
};

onMounted(() => {
  generateBrackets();
  // ajustar vista tras render
  setTimeout(() => fitView(), 150);
});

// Regenera si cambian los lugares
watch(() => props.places, () => {
  generateBrackets();
  setTimeout(() => fitView(), 150);
});
</script>

<template>
  <div class="w-full h-[700px] border border-gray-300 rounded-lg">
    <VueFlow
      :nodes="nodes"
      :edges="edges"
      :node-types="nodeTypes"
      :nodes-draggable="false"
      :fit-view-on-init="true"
      :zoom-on-scroll="false"
      class="bg-white"
    >
      <Background pattern-color="#000" :gap="20" :size="1" />
    </VueFlow>
  </div>
</template>