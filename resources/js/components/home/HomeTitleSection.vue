<script setup lang="ts">

defineProps<{
    title?:string
    subtitle?:string,
    text?:string
}>();

</script>

<template>
    <h2 class="lg:text-7xl md:text-6xl text-3xl font-home font-bold uppercase text-center text-secondary">{{ title??'Sin titulo' }}</h2>
    <h3 v-if="subtitle" class="font-home text-primary text-center text-2xl mt-4">{{ subtitle }}</h3>
    <p v-if="text" class="font-home lg:text-xl text-sm text-center mt-4">{{ text }}</p>
</template>