<script setup lang="ts">

import { Menu, Facebook, Twitter, Youtube } from 'lucide-vue-next';
import { HomeFooter, HomeNav } from '.';
import { ref } from 'vue';

const pages = [
    {text:'Home', url: '/'},
    {text:'Calendario de torneos', url: '/torneos'},
    {text:'Iniciar sesión', url: '/login'},
]

const socialMedia = [
    {url:'#', icon: Facebook},
    {url:'#', icon: Twitter},
    {url:'#', icon: Youtube},
]

const menuOpen = ref(false);

</script>

<template>

    <header>
        <div class="fixed top-5 right-5 bg-black text-white p-5 rounded-sm transition hover:bg-indigo-700 z-10" @click="menuOpen = true"><Menu/></div>    
        <HomeNav :open="menuOpen" :pages="pages" :social-media="socialMedia" @close="menuOpen = false"/>
    </header>

    <slot></slot>

    <HomeFooter :pages="pages" :social-media="socialMedia"/>

</template>