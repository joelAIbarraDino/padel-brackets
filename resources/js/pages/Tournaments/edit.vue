<script setup lang="ts">

import { Head, useForm, usePage } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from "@/components/ui/select";
import { NumberField, NumberFieldContent, NumberFieldDecrement, NumberFieldIncrement, NumberFieldInput } from "@/components/ui/number-field"
import { AppPageProps, BreadcrumbItem, Tournament, TypeTournament } from '@/types';
import { RecordForm, RecordFormBody, RecordFormHeader, RecordFormSubmit } from '@/components/recordForm';
import { computed } from 'vue';
import Swal from 'sweetalert2';

const breadcrumbs: BreadcrumbItem[] = [
    {title:"Torneos", href:"/tournaments"},
    {title:"Crear", href:"#"}
];

interface TypeTournamentsPageProps extends AppPageProps{
    typeTournaments:TypeTournament[],
    tournament:Tournament
}

//convertimos a objeto para el renderizado
const {props} = usePage<TypeTournamentsPageProps>();
const typeTournaments = computed(() => props.typeTournaments);

const form = useForm({
  type:props.tournament.type,
  scheduled_event:props.tournament.scheduled_event,
  admission_price:props.tournament.admission_price,
  places:props.tournament.places,
  modality:props.tournament.modality,
  status:props.tournament.status
});

function submit(){
  form.put(`/tournaments/${props.tournament.id}`,{
    preserveScroll:true,
    onSuccess: () => form.reset(),
  })
}

</script>

<template>
    <Head title="Nuevo tipo de torneo"/>
        <AppLayout :breadcrumbs="breadcrumbs">
      <RecordForm>
        <RecordFormHeader title-form="Nuevo torneo" return-url="/tournaments"/>
        <RecordFormBody  :handle="submit">

            <div class="grid gap-1">
              <Label for="type">Tipo de torneo</Label>

              <Select v-model="form.type" class="px-3 py-2 dark:text-white dark:bg-zinc-900" id="type">
                <SelectTrigger>
                    <SelectValue placeholder="Selecciona el tipo de torneo"/>
                </SelectTrigger>

                <SelectContent>
                  <SelectGroup>
                    <SelectLabel>Tipo de torneos</SelectLabel>
                    <SelectItem v-for="typeTournament in typeTournaments":key="typeTournament.id" :value="typeTournament.id">
                      {{ typeTournament.name }}
                    </SelectItem>
                  </SelectGroup>
                </SelectContent>

              </Select>

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
              <NumberField id="places" :default-value="0" :min="0" :step="2" v-model="form.places">
                <Label for="places">Lugares del torneo</Label>

                <NumberFieldContent>
                  <NumberFieldDecrement/>
                  <NumberFieldInput/>
                  <NumberFieldIncrement/>
                </NumberFieldContent>

              </NumberField>
              <InputError class="mt-1" :message="form.errors.places" />
            </div>

            <div class="grid gap-1">
              <Label for="status">Modalidad de torneo</Label>

              <Select v-model="form.modality" class="px-3 py-2 dark:text-white dark:bg-zinc-900" id="status">
                <SelectTrigger>
                    <SelectValue placeholder="Selecciona la modalidad del torneo"/>
                </SelectTrigger>

                <SelectContent>
                  <SelectGroup>
                    <SelectLabel>Modalidades</SelectLabel>
                    <SelectItem value="Round Robin">Round Robin</SelectItem>
                    <SelectItem value="Eliminación directa">Eliminación directa</SelectItem>
                    <SelectItem value="Doble eliminación">Doble Eliminación</SelectItem>
                    <SelectItem value="Fase de grupos">Fase de grupos + eliminación</SelectItem>
                    <SelectItem value="Americano">Americano</SelectItem>
                    <SelectItem value="Rey de la pista">Rey de la pista</SelectItem>
                  </SelectGroup>
                </SelectContent>

              </Select>
              <InputError class="mt-1" :message="form.errors.modality" />
            </div>

            <div class="grid gap-1">
              <Label for="status">Estado de torneo</Label>

              <Select v-model="form.status" class="px-3 py-2 dark:text-white dark:bg-zinc-900" id="status">
                <SelectTrigger>
                    <SelectValue placeholder="Selecciona el status del torneo"/>
                </SelectTrigger>

                <SelectContent>
                  <SelectGroup>
                    <SelectLabel>Status</SelectLabel>
                    <SelectItem value="activo">Activo</SelectItem>
                    <SelectItem value="inactivo">Inactivo</SelectItem>
                    <SelectItem value="finalizado">Finalizado</SelectItem>
                  </SelectGroup>
                </SelectContent>

              </Select>
              <InputError class="mt-1" :message="form.errors.status" />
            </div>


            <RecordFormSubmit/>
        </RecordFormBody>
      </RecordForm>
    </AppLayout>
</template>