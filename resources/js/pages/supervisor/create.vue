<script setup lang="ts">

import { Head, useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { BreadcrumbItem } from '@/types';
import { RecordForm, RecordFormBody, RecordFormHeader, RecordFormSubmit } from '@/components/recordForm';

const breadcrumbs: BreadcrumbItem[] = [
    {title:"Supervisores", href:"/supervisor"},
    {title:"Crear", href:"#"}
];

const form = useForm({
  name:'',
  email:'',
  password:'',
  password_confirmation:''
});

function submit(){
  form.post('/supervisor',{
    preserveScroll:true,
    onSuccess: () => form.reset()
  })
}

</script>

<template>
    <Head title="Nuevo supervisor"/>
    <AppLayout :breadcrumbs="breadcrumbs">
      <RecordForm>
        <RecordFormHeader title-form="Nuevo supervisor" return-url="/supervisor"/>
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

            <div class="grid gap-1">
              <Label for="password_confirmation">Confirmar contraseña</Label>
              <Input
                  id="password_confirmation"
                  type="password"
                  class="mt-1 block w-full"
                  v-model="form.password_confirmation"
                  placeholder="Correo de usuario"
              />
              <InputError class="mt-1" :message="form.errors.password_confirmation" />
            </div>

            <RecordFormSubmit/>
        </RecordFormBody>
      </RecordForm>
    </AppLayout>
</template>