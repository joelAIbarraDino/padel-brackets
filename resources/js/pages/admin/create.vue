<script setup lang="ts">

import { Head, useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { BreadcrumbItem } from '@/types';
import { RecordForm, RecordFormBody, RecordFormHeader, RecordFormSubmit } from '@/components/recordForm';

const breadcrumbs: BreadcrumbItem[] = [
    {title:"Supervisores", href:"/admin"},
    {title:"Crear", href:"#"}
];

const form = useForm({
  name:'',
  email:'',
  password:''
});

function submit(){
  form.post('/memberships',{
    preserveScroll:true,
    onSuccess: () => form.reset()
  })
}

</script>

<template>
    <Head title="Nueva membresía"/>
    <AppLayout :breadcrumbs="breadcrumbs">
      <RecordForm>
        <RecordFormHeader title-form="Nueva membresía" return-url="/memberships"/>
        <RecordFormBody  :handle="submit">
            
            <div class="grid gap-1">
              <Label for="name">Nombre</Label>
              <Input
                  id="name"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.name"
                  placeholder="Nombre de mebresía"
              />
              <InputError class="mt-1" :message="form.errors.name" />
            </div>

            <div class="grid gap-1">
              <Label for="email">Correo</Label>
              <Input
                  id="email"
                  type="email"
                  class="mt-1 block w-full"
                  v-model="form.email"
                  placeholder="Correo de usuario"
              />
              <InputError class="mt-1" :message="form.errors.email" />
            </div>

            <div class="grid gap-1">
              <Label for="password">Contraseña</Label>
              <Input
                  id="password"
                  type="password"
                  class="mt-1 block w-full"
                  v-model="form.password"
                  placeholder="Correo de usuario"
              />
              <InputError class="mt-1" :message="form.errors.password" />
            </div>

            <RecordFormSubmit/>
        </RecordFormBody>
      </RecordForm>
    </AppLayout>
</template>