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
    {title:"Tipo de torneos", href:"/type-tournaments"},
    {title:"Crear", href:"#"}
];

const attributes = {name:'Nombre de tipo de torneo'}

const form = ref<Partial<{ name:string;}>>({
    name:undefined
});

const resetForm = () =>{
    form.value = {name:undefined};
}

const submit = () =>{
    router.post('/type-tournaments', form.value, {onSuccess:resetForm})
}

</script>

<template>
    <Head title="Nuevo tipo de torneo"/>

    <AppLayout :breadcrumbs="breadcrumbs">
      <Form>
        <FormHeader title-form="Nuevo tipo de torneo" cancel-url="/type-tournaments"/>
        
        <FormBody :handle="submit">
          <div v-for="(label, key) in attributes" :key="key" class="space-y-2">
            <Label :for="key" class="text-sm md:text-lg ">{{label}}</Label>
            <Input 
            :id="key"
            v-model="form[key]"
            :type="'text'"
            :placeholder="label"  
            />
          </div>
          <FormSubmit/>
        </FormBody>
      </Form>
    </AppLayout>
</template>