<script setup lang="ts">

import { Head, useForm, usePage } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { AppPageProps, BreadcrumbItem, Place, Tournament } from '@/types';
import { RecordForm, RecordFormBody, RecordFormHeader, RecordFormSubmit } from '@/components/recordForm';
import { computed } from 'vue';
import Swal from 'sweetalert2';

const breadcrumbs: BreadcrumbItem[] = [
    {title:"Torneos", href:"/tournaments"},
    {title:"Crear", href:"#"}
];

interface TypeTournamentsPageProps extends AppPageProps{
    tournament:Tournament,
    places:Place[]
}

//convertimos a objeto para el renderizado
const {props} = usePage<TypeTournamentsPageProps>();
const tournament = computed(() => props.tournament);
const places = computed(()=>props.places);

const form = useForm({
  first_place:undefined,
  second_place:undefined,
  third_place:undefined,
});

function submit(){
  form.put(`/tournaments/winner/${props.tournament.id}`,{
    preserveScroll:true,
    onSuccess: () => form.reset(),
  })
}

</script>

<template>
    <Head title="Ganadores de torneo"/>
    <AppLayout :breadcrumbs="breadcrumbs">
      <RecordForm>
        <RecordFormHeader title-form="Ganadores de torneo" :return-url="`/places/${tournament.id}`"/>
        <RecordFormBody  :handle="submit">
            
            <div class="grid gap-1">
              <Label for="type">Primer lugar</Label>
              <select
                id="type"   
                v-model="form.first_place"
                class="w-full rounded border px-3 py-2 dark:text-white dark:bg-zinc-900  "
              >
                
                <option 
                  v-for="place in places":key="place.id" 
                  :value="place.user?.id"
                >{{ place.user?.name }}</option>
              </select>
              <InputError class="mt-1" :message="form.errors.first_place" />
            </div>

            <div class="grid gap-1">
              <Label for="type">Segundo lugar</Label>
              <select
                id="type"   
                v-model="form.second_place"
                class="w-full rounded border px-3 py-2 dark:text-white dark:bg-zinc-900  "
              >
                
                <option 
                  v-for="place in places":key="place.id" 
                  :value="place.user?.id"
                >{{ place.user?.name }}</option>
              </select>
              <InputError class="mt-1" :message="form.errors.second_place" />
            </div>
            
            <div class="grid gap-1">
              <Label for="type">Tercer lugar</Label>
              <select
                id="type"   
                v-model="form.third_place"
                class="w-full rounded border px-3 py-2 dark:text-white dark:bg-zinc-900  "
              >
                
                <option 
                  v-for="place in places":key="place.id" 
                  :value="place.user?.id"
                >{{ place.user?.name }}</option>
              </select>
              <InputError class="mt-1" :message="form.errors.third_place" />
            </div>

            <RecordFormSubmit/>
        </RecordFormBody>
      </RecordForm>
    </AppLayout>
</template>