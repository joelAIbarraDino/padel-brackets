<script setup lang="ts">
import {Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow,} from "@/components/ui/table"
import { Pencil, Trash, CirclePlus} from "lucide-vue-next";
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, usePage, Link, router } from '@inertiajs/vue3';
import { Tournament, BreadcrumbItem, AppPageProps} from "@/types";
import {computed} from 'vue';
import Swal from 'sweetalert2';

interface TournamentPageProps extends AppPageProps{
    tournaments:Tournament[];
}

const {props} = usePage<TournamentPageProps>();
const tournaments = computed(() => props.tournaments);

const breadcrumbs: BreadcrumbItem[] = [{title:'Torneos', href:'/tournaments'}]

//eliminar registro
const deleteTournament = async (id: number)=> {

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

    router.delete(`/tournaments/${id}`,{
        preserveScroll:true,
        onSuccess:()=>{
            router.visit('/tournaments', {replace:true});
        },
        onError:(errors)=>{
            console.error("Error al eliminar el registro: ", errors);
            Swal.fire('Error', 'No se pudo eliminar el torneo.', 'error');
        }
    });

}

</script>

<template>
    <Head title="Torneos"/>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex">
                <Button as-child class="bg-lime-600 text-white hover:bg-lime-700">
                    <Link href="/tournaments/create"><CirclePlus/> Crear</Link>
                </Button>
            </div>
            
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min shadow-xl" >
                <Table>
                    <TableCaption>Lista de torneos.</TableCaption>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Tipo de torneo</TableHead>
                            <TableHead>Fecha de evento</TableHead>
                            <TableHead>Precio de admisión</TableHead>
                            <TableHead>Estado</TableHead>
                            <TableHead>Acciones</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="tournament in tournaments" :Key="tournament.id " >
                            <TableCell>{{tournament.type}}</TableCell>
                            <TableCell>{{tournament.scheduled_event}}</TableCell>
                            <TableCell>$ {{tournament.admission_price}}</TableCell>
                            <TableCell>{{tournament.status}}</TableCell>
                            <TableCell class="flex gap-2 justify-start items-center ">
                                <Button as-child size="sm" class="bg-cyan-600 hover:bg-cyan-700 text-white">
                                    <Link :href="`/tournaments/${tournament.id}/edit`">
                                        <Pencil />
                                    </Link>
                                </Button>

                                <Button size="sm" class="bg-red-700 text-white hover:bg-red-600" @click="deleteTournament(tournament.id)"  >
                                    <Trash/>
                                </Button>

                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>
    </AppLayout>
</template>