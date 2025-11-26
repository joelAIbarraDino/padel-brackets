<script setup lang="ts">
import ButtonNewRegister from '@/components/ButtonNewRegister.vue';

import { TableRecordButton, TableActions, TableRecords } from '@/components/tableRecords';

import { AppPageProps, BreadcrumbItem, Membership, User } from '@/types';
import { TableCell, TableRow } from '@/components/ui/table';
import { Head, usePage, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Pencil, Trash} from "lucide-vue-next";
import { computed } from 'vue';
import Swal from 'sweetalert2';

//breadcrumbs de la página
const breadcrumbs: BreadcrumbItem[] = [{title:'Membresías', href:'/memberships'}]

const columnsName = ["Supervisor", "Acciones"]

//interfaz para recuperar los tipos de torneos que nos da PHP
interface TypeTournamentsPageProps extends AppPageProps{
    admins:User[]
}

//convertimos a objeto para el renderizado
const {props} = usePage<TypeTournamentsPageProps>();
const admins = computed(() => props.admins);

//funcion para eliminar un registro
const deleteAdmin = async(id:number)=>{
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

    router.delete(`/admin/${id}`, {
        preserveScroll:true,
        onSuccess:()=>{
            router.visit('/admin', {replace:true});
        },
        onError:(errors)=>{
            console.error("Error al eliminar el registro: ", errors);
            Swal.fire('Error', 'No se pudo eliminar el supervisor.', 'error');   
        }
    });

}


</script>

<template>
    <Head title="Supervisores"/>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">

            <ButtonNewRegister url="/admin/create" text="Crear"/>

            <TableRecords caption="Lista de supervisores" :columns-head="columnsName">
                <TableRow v-for="admin in admins":key="admin.id">
                    <TableCell>{{ admin.name }}</TableCell>
                    

                    <TableActions>
                        <TableRecordButton
                            type="url"
                            color="bg-cyan-600"
                            hover="bg-cyan-700"
                            :icon=Pencil
                            :action="`/memberships/${admin.id}/edit`"
                        />

                        <TableRecordButton
                            type="function"
                            color="bg-red-700"
                            hover="bg-red-600"
                            :icon=Trash
                            :action="() => deleteAdmin(admin.id)"
                        />
                        
                    </TableActions>

                </TableRow>
            </TableRecords>
        </div>
    </AppLayout>

</template>