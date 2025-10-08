<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {title:"Torneos", href:"/tournaments"},
    {title:"Crear", href:"#"}
];

const tournamentAttributes = {type:'Tipo de torneo', scheduled_event: 'Fecha y hora del evento', admission_price: 'Precio de entrada', status:'Estatus del torneo'}

const form = ref<Partial<{ type: number; scheduled_event: string; admission_price: number; status:string}>>({
  type: undefined,
  scheduled_event: undefined,
  admission_price: undefined,
  status: undefined,
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
        <div class="flex flex-1 flex-col gap-4 rounded-xl p-4">
            <h1 class="text-2xl font-bold">Nuevo torneo</h1>
            <form @submit.prevent="submit" class="space-y-6 max-w-lg">
                <div v-for="(label, key) in tournamentAttributes" :key="key" class="space-y-2">
                    <Label :for="key">{{label}}</Label>
                    <Input 
                        :id="key"
                        v-model="form[key]"
                        :type="key==='type'?'number':key==='scheduled_event'?'datetime-local':key==='admission_price'?'number':'text'"
                        :placeholder="label"
                    />
                </div>

                <div class="flex gap-4">
                    <Button type="submit" class="bg-indigo-500 hover:bg-indigo-600">Save</Button>
                    <Button as="a" href="/tournaments" variant="outline">Cancel</Button>
                </div>

            </form>
        </div>
    </AppLayout>
</template>