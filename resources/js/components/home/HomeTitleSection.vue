<script setup lang="ts">

defineProps<{
    title?:string
    subtitle?:string,
    text?:string
}>();

</script>

<template>
    <h2 class="lg:text-7xl md:text-6xl text-5xl font-home font-bold uppercase text-center">{{ title??'Sin titulo' }}</h2>
    <h3 v-if="subtitle" class="font-home text-red-500 text-lg mt-4">{{ subtitle }}</h3>
    <p v-if="text" class="font-home lg:text-xl text-md text-center mt-4">{{ text }}</p>
</template>