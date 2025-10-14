<script setup lang="ts">
import { Form, FormHeader, FormBody, FormSubmit } from '@/components/form';
import { Head, router, usePage } from '@inertiajs/vue3';
import { BreadcrumbItem, TypeTournament } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { reactive, onMounted } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {title:"Torneos", href:"/tournaments"},
    {title:"Editar", href:"#"}
];

const {props} = usePage();
const tournament = props.typeTournament as TypeTournament;

const attributes = {name:'Nombre de tipo de torneo'};

const form = reactive<Partial<{ name: string}>>({
    name:undefined
});

onMounted(()=>{
    form.name = tournament.name??undefined
});

const resetForm = () =>{
    form.name = tournament.name
}

const submit = () =>{
    router.put(`/type-tournaments/${tournament.id}`, form, {onSuccess:resetForm})
}

</script>

<template>
    <Head title="Editar tipo de torneo"/>

    <AppLayout :breadcrumbs="breadcrumbs">
      <Form>
        <FormHeader title-form="Editar tipo de torneo" cancel-url="/type-tournaments"/>
        
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