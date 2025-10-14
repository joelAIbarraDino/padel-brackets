<script setup lang="ts">

import { Head, useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { BreadcrumbItem } from '@/types';
import { RecordForm, RecordFormBody, RecordFormHeader, RecordFormSubmit } from '@/components/recordForm';

const breadcrumbs: BreadcrumbItem[] = [
    {title:"Tipo de torneos", href:"/type-tournaments"},
    {title:"Crear", href:"#"}
];

const form = useForm({
  name:''
});

function submit(){
  form.post('/type-tournaments',{
    preserveScroll:true,
    onSuccess: () => form.reset()
  })
}

</script>

<template>
    <Head title="Nuevo tipo de torneo"/>
    <AppLayout :breadcrumbs="breadcrumbs">
      <RecordForm>
        <RecordFormHeader title-form="Nuevo registro" return-url="/type-tournaments"/>
        <RecordFormBody  :handle="submit">
            <div class="grid gap-1">
              <Label for="name">Tipo de torneo</Label>
              <Input
                  id="name"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.name"
                  placeholder="Nombre de tipo de torneo"
              />
              <InputError class="mt-1" :message="form.errors.name" />
            </div>
            <RecordFormSubmit/>
        </RecordFormBody>
      </RecordForm>
    </AppLayout>
</template>