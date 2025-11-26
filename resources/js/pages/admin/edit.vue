<script setup lang="ts">

import { Head, useForm, usePage } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Input } from '@/components/ui/input';
import { Textarea } from "@/components/ui/textarea"
import { Label } from '@/components/ui/label';
import { AppPageProps, BreadcrumbItem, Membership } from '@/types';
import { RecordForm, RecordFormBody, RecordFormHeader, RecordFormSubmit } from '@/components/recordForm';
import { NumberField, NumberFieldContent, NumberFieldDecrement, NumberFieldIncrement, NumberFieldInput } from "@/components/ui/number-field"
import { computed } from 'vue';
import password from '@/routes/password';

const breadcrumbs: BreadcrumbItem[] = [
    {title:"Supervisores", href:"/memberships"},
    {title:"Editar", href:"#"}
];

interface MembershipsPageProps extends AppPageProps{
    membership:Membership
}

const {props} = usePage<MembershipsPageProps>();
const membership = computed(() => props.membership);

const form = useForm({
  name:membership.value.name,
  email:membership.value.description
});

function submit(){
  form.put(`/memberships/${membership.value.id}`,{
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

            <RecordFormSubmit/>
        </RecordFormBody>
      </RecordForm>
    </AppLayout>
</template>