<script setup lang="ts">
import { HomePageName, PublicAppLayout } from '@/components/home';
import { CalendarResponsive } from '@/components/calendar';
import { Head, usePage } from '@inertiajs/vue3';
import { AppPageProps } from '@/types';
import { computed } from 'vue';

interface EventageProps extends AppPageProps{
    events:[];
}

const {props} = usePage<EventageProps>();
const events = computed(() => props.events);

</script>

<template>
    <Head title="Torneos"/>

    <PublicAppLayout>  
      <HomePageName title="Calendario de torneos"/>
      <CalendarResponsive :events="events" />
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