<script setup lang="ts">
import ButtonNewRegister from '@/components/ButtonNewRegister.vue';

import { TableRecordButton, TableActions, TableRecords } from '@/components/tableRecords';

import { AppPageProps, BreadcrumbItem, Membership } from '@/types';
import { TableCell, TableRow } from '@/components/ui/table';
import { Head, usePage, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Pencil, Trash} from "lucide-vue-next";
import { computed } from 'vue';
import Swal from 'sweetalert2';

//breadcrumbs de la página
const breadcrumbs: BreadcrumbItem[] = [{title:'Membresías', href:'/memberships'}]

const columnsName = ["Nombre de membresía", "Precio", "Meses para expirar", "Acciones"]

//interfaz para recuperar los tipos de torneos que nos da PHP
interface TypeTournamentsPageProps extends AppPageProps{
    memberships:Membership[]
}

//convertimos a objeto para el renderizado
const {props} = usePage<TypeTournamentsPageProps>();
const memberships = computed(() => props.memberships);

//funcion para eliminar un registro
const deleteMembership = async(id:number)=>{
    const result = await Swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás revertir esto!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    });

    if(!result.isConfirmed)return;

    router.delete(`/memberships/${id}`, {
        preserveScroll:true,
        onSuccess:()=>{
            router.visit('/memberships', {replace:true});
        },
        onError:(errors)=>{
            console.error("Error al eliminar el registro: ", errors);
            Swal.fire('Error', 'No se pudo eliminar la membresía.', 'error');   
        }
    });

}


</script>

<template>
    <Head title="Membresías"/>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">

            <ButtonNewRegister url="/memberships/create" text="Crear"/>

            <TableRecords caption="Lista de membresías" :columns-head="columnsName">
                <TableRow v-for="membership in memberships":key="membership.id">
                    <TableCell>{{ membership.name }}</TableCell>
                    <TableCell>$ {{ membership.price }}</TableCell>
                    <TableCell>{{ membership.months_to_expire }}</TableCell>

                    <TableActions>
                        <TableRecordButton
                            type="url"
                            color="bg-cyan-600"
                            hover="bg-cyan-700"
                            :icon=Pencil
                            :action="`/memberships/${membership.id}/edit`"
                        />

                        <TableRecordButton
                            type="function"
                            color="bg-red-700"
                            hover="bg-red-600"
                            :icon=Trash
                            :action="() => deleteMembership(membership.id)"
                        />
                        
                    </TableActions>

                </TableRow>
            </TableRecords>
        </div>
    </AppLayout>

</template>