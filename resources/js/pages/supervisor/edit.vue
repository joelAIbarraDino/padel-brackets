<script setup lang="ts">

import { Head, useForm, usePage } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { AppPageProps, BreadcrumbItem, User } from '@/types';
import { RecordForm, RecordFormBody, RecordFormHeader, RecordFormSubmit } from '@/components/recordForm';
import { computed } from 'vue';


const breadcrumbs: BreadcrumbItem[] = [
    {title:"Supervisores", href:"/supervisor"},
    {title:"Editar", href:"#"}
];

interface SupervisorPageProps extends AppPageProps{
    supervisor:User
}

const {props} = usePage<SupervisorPageProps>();
const supervisor = computed(() => props.supervisor);

const form = useForm({
  name:supervisor.value.name,
  email:supervisor.value.email
});

function submit(){
  form.put(`/supervisor/${supervisor.value.id}`,{
    preserveScroll:true,
    onSuccess: () => form.reset()
  })
}

</script>

<template>
    <Head title="Editar supervisor"/>
    <AppLayout :breadcrumbs="breadcrumbs">
      <RecordForm>
        <RecordFormHeader title-form="Editar supervisor" return-url="/supervisor"/>
        <RecordFormBody  :handle="submit">
            
            <div class="grid gap-1">
              <Label for="name">Nombre</Label>
              <Input
                  id="name"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.name"
                  placeholder="Nombre de supervisor"
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
                  placeholder="Correo de supervisor"
              />
              <InputError class="mt-1" :message="form.errors.email" />
            </div>

            <RecordFormSubmit/>
        </RecordFormBody>
      </RecordForm>
    </AppLayout>
</template>