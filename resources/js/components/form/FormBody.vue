<script setup lang="ts">

const props = defineProps<{
    handle:() => void;
}>();

</script>


<template>
    <form @submit.prevent="handle" class="space-y-6">
        <slot></slot>
    </form>
</template>