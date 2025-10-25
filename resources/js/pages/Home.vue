<script setup lang="ts">
import { MoveRight, Calendar, Shirt, Trophy } from 'lucide-vue-next';
import { HomeTitleSection, PublicAppLayout} from '@/components/home';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { EffectCoverflow, Pagination } from 'swiper/modules';
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'
import { EventClickArg } from '@fullcalendar/core/index.js';


import 'swiper/css';
import 'swiper/css/pagination';
import 'swiper/css/effect-coverflow';
import { AppPageProps } from '@/types';
import { computed, ref } from 'vue';

const membresias = [
  { titulo: 'Membresía Básica', descripcion: 'Acceso a torneos locales.', precio: '$19 / mes', color: 'text-indigo-400' },
  { titulo: 'Membresía Pro', descripcion: 'Torneos premium y recompensas.', precio: '$39 / mes', color: 'text-pink-400' },
  { titulo: 'Membresía Elite', descripcion: 'Soporte personalizado y acceso ilimitado.', precio: '$59 / mes', color: 'text-yellow-400' }
];

interface EventageProps extends AppPageProps{
    events:[];
}

const {props} = usePage<EventageProps>();
const events = computed(() => props.events);


const calendarOptions = ref({
  plugins: [dayGridPlugin, interactionPlugin],
  initialView: 'dayGridMonth',
  events: events,
  eventClick: function(info:EventClickArg) {
    // Redirige al hacer click
    window.location.href = info.event.url
    info.jsEvent.preventDefault()
  }
})

</script>

<template>
    <Head title="Padel"></Head>

    <PublicAppLayout>
        
        <section class="relative w-full h-screen bg-[url('@/assets/slider.jpg')] bg-no-repeat bg-cover bg-top-left">
            <Link href="/">
                <img src="@/assets/logo.jpg" alt="Logo de página de torneo de padel" class="w-1/4 md:w-1/6 transition hover:opacity-90 absolute top-5 left-5">
            </Link>

            <div class="absolute left-0 right-0 w-full h-full flex items-center justify-end">
                <div class="font-home flex flex-col gap-4 items-center lg:w-4xl md:w-2xl w-full">
                    <h2 class="font-bold text-5xl md:text-6xl lg:text-8xl text-white text-center slider animate-fade-slide-left">Eleva tu juego al siguiente <span class="text-red-500">nivel</span></h2>
                    <p class="text-white text-xl md:text-2xl lg:text-4xl font-medium text-center">Eleva el juego al siguiente nivel</p>
                    <Button class="py-8 px-16 text-white rounded-sm text-md md:text-xl lg:text-2xl font-medium bg-indigo-700 hover:bg-indigo-600 transition duration-300 ease-out hover:shadow-lg animate-fade-slide-up ">
                        <Link href="/torneos" class="flex items-center justify-center gap-4">¡Reserva ahora! <MoveRight/></Link>
                    </Button>
                </div>
            </div>

        </section>

        <section class="bg-white p-20 text-center">
            <HomeTitleSection title="Unete a la experiencia" subtitle="Más que un juego"/>

            <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5 mt-10">
                <div class="flex flex-col items-center gap-5">
                    <div class="rounded-full bg-indigo-700 size-30 flex items-center justify-center text-white"><Calendar :size="60"/></div>
                    <p class="text-2xl font-home font-bold">Eventos comunitarios</p>
                </div>

                <div class="flex flex-col items-center gap-5">
                    <div class="rounded-full bg-indigo-700 size-30 flex items-center justify-center text-white"><Trophy :size="60"/></div>
                    <p class="text-2xl font-home font-bold">Torneos y partidos</p>
                </div>

                <div class="flex flex-col items-center gap-5">
                    <div class="rounded-full bg-indigo-700 size-30 flex items-center justify-center text-white"><Shirt :size="60"/></div>
                    <p class="text-2xl font-home font-bold">Tienda especializada</p>
                </div>
            </div>

        </section>

        <section class="bg-slate-950 flex flex-col md:flex-row">
            <img src="@/assets/why-us.jpg" class="h-full w-full md:w-1/2">

            <div class="flex items-center justify-center flex-col font-home text-white p-10">
                    <HomeTitleSection 
                        title="Vive la emoción del pádel como nunca antes"
                        subtitle="Aprende a jugar"
                        text="Lorem ipsum dolor sit amet consectetur adipisicing elit. Quidem reiciendis beatae fugiat voluptatem doloremque velit tempora ea praesentium a voluptate! "
                    />

                    <div class="mt-15">
                        <div class="flex items-center justify-start gap-10 mt-10" v-for="i in 3":key="i">
                            <div>
                                <h4 class="text-2xl font-black">Lorem ipsum dolor sit amet consectetur.</h4>
                                <p>
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatem dicta esse obcaecati nemo aut amet eum pariatur fuga repellat expedita.
                                </p>
                            </div>
                        </div>
                    </div>
            </div>

        </section>

        <section class="text-center p-20 bg-neutral-100">
            <HomeTitleSection
                title="Proximos torneos"
                subtitle="Inscribete ya al proximo torneo y demuestra de que estas hecho"
            />
            <FullCalendar :options="calendarOptions" class="p-10 w-7/10 mx-auto"/>
        </section>

        <section class="py-35 px-20">
            <HomeTitleSection 
                title="Aprovecha las membresías que tenemos para tí"
                text="Lorem ipsum dolor sit amet consectetur adipisicing elit. Quidem reiciendis beatae fugiat voluptatem doloremque velit tempora ea praesentium a voluptate! "
            />

            <Swiper
                :modules="[EffectCoverflow, Pagination]"
                effect="coverflow"
                :grab-cursor="true"
                :centered-slides="true"
                slides-per-view="auto"
                :loop="true"
                :coverflow-effect="{
                    rotate: 20,
                    stretch: 0,
                    depth: 200,
                    modifier: 1,
                    slideShadows: true
                }"
                :autoplay="{
                    delay: 3000
                }"
                :pagination="{ 
                    el: '.swiper-pagination',
                    clickable: true
                }"
                class="w-9/10 md:w-5/10 mx-auto mt-10"
            >
                <SwiperSlide
                    v-for="(membresia, i) in membresias"
                    :key="i"
                    class="bg-gray-700 rounded-2xl p-8  pb-12 shadow-lg flex flex-col items-center justify-center text-center"
                    >
                    <h3 class="text-2xl font-semibold mb-4 text-center text-white">{{ membresia.titulo }}</h3>
                    <p class="text-gray-300 mb-6">{{ membresia.descripcion }}</p>
                    <div class="flex flex-col items-center justify-center gap-4">
                        <span class="text-3xl font-bold" :class="membresia.color">{{ membresia.precio }}</span>
                        <Button class="bg-indigo-500 hover:bg-indigo-400 text-white px-6 py-2 rounded-full transition text-2xl font-home font-bold" size="lg">
                            <Link href="/membresia/#">Elegir</Link>
                        </Button>
                    </div>
                </SwiperSlide>
                <div class="swiper-pagination mt-30"></div>
            </Swiper>
            
            
        </section>
        
        <section class="bg-linear-to-l from-indigo-800 via-indigo-600 to-indigo-500 p-20">
            <h2 class="lg:text-7xl md:text-6xl text-5xl font-home font-bold uppercase text-center text-white">Siguenos en <span class="text-red-500">#padel_torneos</span></h2>        
        </section>
    
        
    </PublicAppLayout>
    
</template>

<style scoped>
@keyframes fade-slide-down {
    0% {
        opacity: 0;
        transform: translateY(-40px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fade-slide-up {
    0% {
        opacity: 0;
        transform: translateY(50px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fade-slide-left{
    0% {
        opacity: 0;
        transform: translateX(50px);
    }
    100% {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Clases animadas */
.animate-fade-slide-down {
  animation: fade-slide-down 0.8s ease-out forwards;
}

.animate-fade-slide-up {
  animation: fade-slide-up 0.8s ease-out 0.2s forwards;
}

.animate-fade-slide-left {
  animation: fade-slide-left 0.5s ease-out 0.2s forwards;
}


</style>