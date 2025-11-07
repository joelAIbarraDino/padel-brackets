<script setup lang="ts">
import { LucideIcon } from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';


defineProps<{
    pages:{
        url:string,
        text:string
    }[],
    socialMedia:{
        url:string,
        icon:LucideIcon
    }[]
}>();
</script>

<template>
    
    <footer class="bg-primary text-white py-10 md:px-30 px-10">
        <div class="flex items-sart justify-between flex-col md:flex-row">
            <nav>
                <ul class="flex flex-col">
                    <li v-for="page in pages":key="page.url" class="text-2xl text-white hover:text-gray-200 py-2 px-4 pl-0 font-home font-bold">
                    <Link :href="page.url">{{ page.text }}</Link> 
                    </li>
                </ul>
            </nav>

            <div class="text-gray-200 mt-2 md:mt-0">
                <p>@{{ new Date().getFullYear() }} Lomas Padel</p>
                <ul class="flex gap-2 mt-2">
                    <li v-for="media in socialMedia":key="media.url" class="text-white p-2 transition hover:bg-gray-200 hover:text-black pl-0">
                        <Link :href="media.url"><component :is="media.icon" :size="20"/></Link>
                    </li>
                </ul>
            </div>
        </div>
    </footer>
</template>