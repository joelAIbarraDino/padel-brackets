<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
import type { EventClickArg } from "@fullcalendar/core";
import { Link } from "@inertiajs/vue3";

// Props
const props = defineProps<{ events: any[] }>();

// Responsive
const isMobile = ref(false);

const updateResponsive = () => {
    isMobile.value = window.innerWidth < 1024;
};

onMounted(() => {
    updateResponsive();
    window.addEventListener("resize", updateResponsive);
});

// Calendar config
const calendarOptions = computed(() => ({
    plugins: [dayGridPlugin, interactionPlugin],
    initialView: "dayGridMonth",
    events: props.events,
    eventClick: (info: EventClickArg) => {
        info.jsEvent.preventDefault();
        if (info.event.url) window.location.href = info.event.url;
    }
}));

// Lista móvil
const eventList = computed(() => {
    const evs = props.events || [];

    const months = [
        "Enero","Febrero","Marzo","Abril","Mayo","Junio",
        "Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"
    ];

    const grouped: any = {};

    evs.forEach(ev => {
        const date = new Date(ev.start);
        const key = `${date.getFullYear()}-${date.getMonth()}`;

        if (!grouped[key]) {
            grouped[key] = {
                label: `${months[date.getMonth()]} ${date.getFullYear()}`,
                items: []
            };
        }

        grouped[key].items.push({
            ...ev,
            date,
            day: date.getDate(),
            hour: date.toLocaleTimeString("es-MX", {
                hour: "2-digit",
                minute: "2-digit",
            })
        });
    });

    return Object.values(grouped).map((g: any) => {
        g.items.sort((a: any, b: any) => a.date - b.date);
        return g;
    });
});
</script>

<template>
    <div class="p-10 w-7/10 mx-auto text-black">

        <div v-if="!isMobile">
            <FullCalendar :options="calendarOptions" />
        </div>

        <div v-if="isMobile && props.events.length === 0" class="text-center py-6 text-primary text-5xl font-bold font-home">
            No hay proximos eventos
        </div>

        <div v-else-if="isMobile" class="flex flex-col gap-10">
            <div v-for="group in eventList" :key="group.label">
                <h3 class="text-3xl font-bold mb-4 text-primary">
                    {{ group.label }}
                </h3>
                
                <Link
                    v-for="ev in group.items":key="ev.id"
                    :href="ev.url"
                >

                    <h4 class="text-xl font-bold">{{ ev.title }}</h4>
                    <p class="text-sm text-neutral-600">
                        {{ ev.day }} {{ group.label.split(' ')[0] }} – {{ ev.hour }}
                    </p>

                </Link>
            </div>
        </div>

    </div>
</template>
