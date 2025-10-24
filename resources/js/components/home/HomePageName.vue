<script setup lang="ts">

defineProps<{
    title:string
}>();
</script>

<template>
    <div class="bg-[url('@/assets/coverName.jpg')] bg-no-repeat bg-cover bg-center py-30 px-10 relative">
        <div class="absolute top-0 left-0 w-full h-full flex items-center justify-center bg-indigo-700 opacity-60">
            <h1 class="text-2xl md:text-7xl text-white text-center font-home font-bold z-10">{{ title }}</h1>
        </div>
    </div>
</template>