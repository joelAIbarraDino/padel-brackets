<script setup lang="ts">

import { Head, useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Input } from '@/components/ui/input';
import { Textarea } from "@/components/ui/textarea"
import { Label } from '@/components/ui/label';
import { BreadcrumbItem } from '@/types';
import { RecordForm, RecordFormBody, RecordFormHeader, RecordFormSubmit } from '@/components/recordForm';
import { NumberField, NumberFieldContent, NumberFieldDecrement, NumberFieldIncrement, NumberFieldInput } from "@/components/ui/number-field"

const breadcrumbs: BreadcrumbItem[] = [
    {title:"Membresías", href:"/memberships"},
    {title:"Crear", href:"#"}
];

const form = useForm({
  name:'',
  description:'',
  months_to_expire:undefined,
  price:undefined
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
                <Label for="description">Descripción</Label>
                <Textarea 
                    id="description" 
                    placeholder="Descripción de membresía" 
                    v-model="form.description"
                    class="mt-1 block w-full"
                />
                <InputError class="mt-1" :message="form.errors.description" />
            </div>

            <div class="grid gap-1">
                <NumberField id="months_to_expire" :default-value="0" :min="0" v-model="form.months_to_expire">
                    <Label for="months_to_expire">Meses de periodo</Label>

                    <NumberFieldContent>
                        <NumberFieldDecrement/>
                        <NumberFieldInput/>
                        <NumberFieldIncrement/>
                    </NumberFieldContent>

                </NumberField>
              <InputError class="mt-1" :message="form.errors.months_to_expire" />
            </div>

            <div class="grid gap-1">
                <Label for="price">Precio</Label>
                <Input
                    id="price"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.price"
                />
                <InputError class="mt-1" :message="form.errors.price" />
            </div>

            <RecordFormSubmit/>
        </RecordFormBody>
      </RecordForm>
    </AppLayout>
</template>