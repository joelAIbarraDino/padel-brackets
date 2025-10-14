<script setup lang="ts">
import { Form, FormHeader, FormSubmit } from '@/components/form';
import FormBody from '@/components/form/FormBody.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { BreadcrumbItem } from '@/types';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {title:"Torneos", href:"/tournaments"},
    {title:"Crear", href:"#"}
];

const tournamentAttributes = {type:'Tipo de torneo', scheduled_event: 'Fecha y hora del evento', admission_price: 'Precio de entrada', status:'Estatus del torneo'}

const form = ref<Partial<{ type: number; scheduled_event: string; admission_price: number; status:string}>>({
  type: undefined,
  scheduled_event: undefined,
  admission_price: undefined,
  status: 'activo',
});

const resetForm = () =>{
    form.value = {type:undefined, scheduled_event:undefined, admission_price:undefined, status:undefined};
}

const submit = () =>{
    router.post('/tournaments', form.value, {onSuccess:resetForm})
}

</script>

<template>
    <Head title="Crear torneo"/>

    <AppLayout :breadcrumbs="breadcrumbs">
      <Form>
        <FormHeader title-form="Nuevo torneo" cancel-url="/tournaments"/>
        
        <FormBody :handle="submit">
          <div v-for="(label, key) in tournamentAttributes" :key="key" class="space-y-2">
            <Label :for="key" class="text-sm md:text-lg ">{{label}}</Label>
            <template v-if="key === 'status'">
              <select
                :id="key"   
                v-model="form.status"
                class="w-full rounded border px-3 py-2 dark:text-white dark:bg-zinc-900  "
              >
                <option value="" disabled>Selecciona un estatus</option>
                <option value="activo" selected>Activo</option>
                <option value="inactivo">Inactivo</option>
                <option value="finalizado">Finalizado</option>
              </select>
            </template>
            <template v-else>
              <Input 
                :id="key"
                v-model="form[key]"
                :type="key==='type'?'number':key==='scheduled_event'?'datetime-local':key==='admission_price'?'number':'text'"
                :placeholder="label"  
              />
            </template>
          </div>
          <FormSubmit/>
        </FormBody>
      </Form>
    </AppLayout>
</template>