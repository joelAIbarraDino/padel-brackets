<script setup lang="ts">
import { dashboard, login, register } from '@/routes';
import { Head, Link, usePage } from '@inertiajs/vue3';
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'
import { AppPageProps, Event } from '@/types';
import { computed, ref } from 'vue';
import { EventClickArg } from '@fullcalendar/core/index.js';


interface EventageProps extends AppPageProps{
    events:[];
}

const {props} = usePage<EventageProps>();
const events = computed(() => props.events);

const calendarOptions = ref({
  plugins: [dayGridPlugin, interactionPlugin],
  initialView: 'dayGridMonth',
  events: events,
  eventClick: function(info:EventClickArg) {
    // Redirige al hacer click
    window.location.href = info.event.url
    info.jsEvent.preventDefault()
  }
})

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
            <h1>Torneos registrados</h1>
            <FullCalendar :options="calendarOptions" />
        </main>

    </div>

</template>

<style>
.fc {
  --fc-border-color: #e5e7eb;
  --fc-button-bg-color: #0369a1;
  --fc-button-hover-bg-color: #0284c7;
  --fc-button-active-bg-color: #075985;
  --fc-event-bg-color: #0284c7;
  --fc-event-border-color: #0369a1;
  --fc-event-text-color: white;
}
</style>