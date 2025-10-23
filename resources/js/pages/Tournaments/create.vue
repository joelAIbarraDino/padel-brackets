<script setup lang="ts">

import { Head, useForm, usePage } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { AppPageProps, BreadcrumbItem, TypeTournament } from '@/types';
import { RecordForm, RecordFormBody, RecordFormHeader, RecordFormSubmit } from '@/components/recordForm';
import { computed } from 'vue';
import Swal from 'sweetalert2';

const breadcrumbs: BreadcrumbItem[] = [
    {title:"Torneos", href:"/tournaments"},
    {title:"Crear", href:"#"}
];

interface TypeTournamentsPageProps extends AppPageProps{
    typeTournaments:TypeTournament[];
}

//convertimos a objeto para el renderizado
const {props} = usePage<TypeTournamentsPageProps>();
const typeTournaments = computed(() => props.typeTournaments);

const form = useForm({
  type:undefined,
  scheduled_event:undefined,
  admission_price:'',
  places:undefined, 
  status:'activo'
});

function submit(){
  form.post('/tournaments',{
    preserveScroll:true,
    onSuccess: () => form.reset(),
    onError:() => Swal.fire('Error', 'No se pudo registrar el torneo.', 'error')
  })
}

</script>

<template>
    <Head title="Nuevo tipo de torneo"/>
    <AppLayout :breadcrumbs="breadcrumbs">
      <RecordForm>
        <RecordFormHeader title-form="Nuevo registro" return-url="/tournaments"/>
        <RecordFormBody  :handle="submit">

            <div class="grid gap-1">
              <Label for="type">Tipo de torneo</Label>
              <select
                id="type"   
                v-model="form.type"
                class="w-full rounded border px-3 py-2 dark:text-white dark:bg-zinc-900  "
              >
                
                <option 
                  v-for="typeTournament in typeTournaments":key="typeTournament.id" 
                  :value="typeTournament.id"
                >{{ typeTournament.name }}</option>
              </select>
              <InputError class="mt-1" :message="form.errors.type" />
            </div>
            
            <div class="grid gap-1">
              <Label for="scheduled_event">Fecha y hora de evento</Label>
              <Input
                  id="scheduled_event"
                  type="datetime-local"
                  class="mt-1 block w-full"
                  v-model="form.scheduled_event"
              />
              <InputError class="mt-1" :message="form.errors.scheduled_event" />
            </div>

            <div class="grid gap-1">
              <Label for="admission_price">Precio de entrada</Label>
              <Input
                  id="admission_price"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.admission_price"
              />
              <InputError class="mt-1" :message="form.errors.admission_price" />
            </div>

            <div class="grid gap-1">
              <Label for="places">Lugares del torneo</Label>
              <Input
                  id="places"
                  type="number"
                  class="mt-1 block w-full"
                  v-model="form.places"
              />
              <InputError class="mt-1" :message="form.errors.places" />
            </div>


            <div class="grid gap-1">
              <Label for="status">Estado de torneo</Label>
              <select
                id="status"   
                v-model="form.status"
                class="w-full rounded border px-3 py-2 dark:text-white dark:bg-zinc-900  "
              >
                <option value="" disabled>Selecciona un estatus</option>
                <option value="activo" selected>Activo</option>
                <option value="inactivo">Inactivo</option>
                <option value="finalizado">Finalizado</option>
              </select>
              <InputError class="mt-1" :message="form.errors.status" />
            </div>


            <RecordFormSubmit/>
        </RecordFormBody>
      </RecordForm>
    </AppLayout>
</template>