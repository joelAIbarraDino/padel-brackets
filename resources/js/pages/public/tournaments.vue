<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'
import { AppPageProps, Event } from '@/types';
import { computed, ref } from 'vue';
import { EventClickArg } from '@fullcalendar/core/index.js';
import { HomePageName, PublicAppLayout } from '@/components/home';


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

    <PublicAppLayout>  
      <HomePageName title="Calendario de torneos"/>
      <FullCalendar :options="calendarOptions" class="p-10 w-7/10 mx-auto"/>
    </PublicAppLayout>
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