<script setup lang="ts">

import { Head, useForm, usePage } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from "@/components/ui/select";
import { AppPageProps, BreadcrumbItem, Place, Tournament } from '@/types';
import { RecordForm, RecordFormBody, RecordFormHeader, RecordFormSubmit } from '@/components/recordForm';
import { computed } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {title:"Torneos", href:"/tournaments"},
    {title:"Editar lugar", href:"#"}
];

interface TypeTournamentsPageProps extends AppPageProps{
    place:Place,
    tournaments:Tournament[]
}

//convertimos a objeto para el renderizado
const {props} = usePage<TypeTournamentsPageProps>();

const place = computed(() => props.place);
const tournaments = computed(() => props.tournaments);
const currentTournamentID = place.value.id_tournament;

const form = useForm({
  tournament:undefined
});

function submit(){
  form.put(`/places/reschedule/${props.place.id}`,{
    preserveScroll:true,
    onSuccess: () => form.reset(),
  })
}

</script>

<template>
    <Head title="Reagendar torneo"/>
        <AppLayout :breadcrumbs="breadcrumbs">
      <RecordForm>
        <RecordFormHeader title-form="Reagendar lugar de torneo" :return-url="`/places/${currentTournamentID}`"/>
        <RecordFormBody  :handle="submit">

            <div class="grid gap-1">
              <Label for="type">Cambiar torneo</Label>

              <Select v-model="form.tournament" class="px-3 py-2 dark:text-white dark:bg-zinc-900" id="type">
                <SelectTrigger>
                    <SelectValue placeholder="Selecciona un nuevo torneo"/>
                </SelectTrigger>

                <SelectContent>
                  <SelectGroup>
                    <SelectLabel>Torneos disponibles</SelectLabel>
                    <SelectItem v-for="tournament in tournaments":key="tournament.id" :value="tournament.id">
                      # {{ tournament.id }} - {{ tournament.scheduled_event }}
                    </SelectItem>
                  </SelectGroup>
                </SelectContent>

              </Select>

              <InputError class="mt-1" :message="form.errors.tournament" />
            </div>
            
            <RecordFormSubmit/>

        </RecordFormBody>
      </RecordForm>
    </AppLayout>
</template>