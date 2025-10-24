<script setup lang="ts">
import { X, Facebook, Twitter, Youtube, LucideIcon } from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';

const props = defineProps<{ 
    open: boolean,
    pages:{
        url:string,
        text:string
    }[],
    socialMedia:{
        url:string,
        icon:LucideIcon
    }[]

}>();
const emit = defineEmits(["close"]);

const closeMenu = () => emit("close");


</script>

<template>
    <transition name="slide-vertical">
        <div 
            v-if="props.open"
            class="bg-indigo-700 w-full h-screen fixed inset-0 z-20"
        >

            <div class="absolute top-0 left-0 w-full p-5 flex items-center justify-between">
                <Link href="/">
                    <img src="@/assets/logo.jpg" alt="Logo de página de torneo de padel" class="w-1/4 md:w-1/6 transition hover:opacity-90">
                </Link>
                
                <div class="bg-white text-red-400 p-5 transition hover:text-indigo-700" @click="closeMenu">
                    <X/>
                </div>
            </div>

            <nav class="absolute top-1/3 left-0 w-full flex flex-col items-center justify-between">
                <ul class="flex flex-col gap-2">
                    <li v-for="page in pages":key="page.url" class="text-4xl text-white hover:bg-indigo-600 py-2 px-4 font-home font-thin ">
                    <Link :href="page.url">{{ page.text }}</Link> 
                    </li>
                </ul>
            </nav>
        
            <ul class="absolute left-1/4 bottom-10 flex items-center justify-center gap-3 border-t pt-4 w-1/2">
                <li v-for="socialMedia in socialMedia":key="socialMedia.url" class="text-white transition hover:text-amber-100">
                    <Link :href="socialMedia.url"><component :is="socialMedia.icon" :size="20"/></Link>
                </li> 
            </ul>
        </div>
    </transition>
</template>

<style scoped>
    .slide-vertical-enter-active,
    .slide-vertical-leave-active {
        transition: all 0.2s cubic-bezier(0.25, 1, 0.5, 1);
    }

    .slide-vertical-enter-from {
        transform: translateY(-100%);
        opacity: 0;
    }

    .slide-vertical-enter-to {
        transform: translateY(0);
        opacity: 1;
    }

    .slide-vertical-leave-from {
        transform: translateY(0);
        opacity: 1;
    }

    .slide-vertical-leave-to {
        transform: translateY(-100%);
        opacity: 0;
    }

</style>