<script setup lang="ts">
import { MoveRight, Users, GraduationCap, Trophy, FlameKindling, WavesLadder, ShowerHead, Sun, Thermometer } from 'lucide-vue-next';
import { HomeTitleSection, PublicAppLayout} from '@/components/home';
import { Accordion, AccordionContent, AccordionItem, AccordionTrigger } from "@/components/ui/accordion"
import { Head, Link, usePage } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Pagination } from 'swiper/modules';
import FullCalendar from '@fullcalendar/vue3'

import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'
import { EventClickArg } from '@fullcalendar/core/index.js';


import 'swiper/css';
import 'swiper/css/pagination';
import 'swiper/css/effect-coverflow';
import { AppPageProps, Membership } from '@/types';
import { computed, ref } from 'vue';

const defaultValue = "item-1"

const accordionItems = [
  { value: "item-1", title: "Round Robin", content: "Cada pareja juega contra todas las demás en su grupo. La suma de puntos o partidos ganados define a los clasificados. Ideal para asegurar que todos jueguen varios partidos. Útil en torneos sociales o de pocos equipos." },
  { value: "item-2", title: "Eliminación directa", content: "Pierdes un partido y quedas fuera. Rápido, dinámico y sencillo de organizar. Ideal para fases finales o torneos con muchos equipos y poco tiempo." },
  { value: "item-3", title: "Doble eliminación", content: "Cada pareja debe perder dos veces para quedar eliminada. Permite una segunda oportunidad en el \“cuadro de perdedores\”. Justo y competitivo, pero más largo que la eliminación directa." },
  { value: "item-4", title: "Fase de grupos + eliminación", content: "Primera ronda en grupos (round robin corto). Los mejores pasan a cuartos/semifinal/final por eliminación directa. Equilibrio entre partidos garantizados y emoción de fases finales." },
  { value: "item-5", title: "Americano", content: "Modalidad recreativa, rápida y flexible. Los jugadores cambian constantemente de pareja y rivales en cada mini-partido. Se suman puntos individuales, no por equipo fijo." },
  { value: "item-6", title: "Rey de la pista", content: "Parejas rotan en una pista: los ganadores permanecen y los perdedores rotan y vuelven a intentar." },
];



interface EventageProps extends AppPageProps{
    events:[];
    memberships:Membership[]
}

const {props} = usePage<EventageProps>();
const events = computed(() => props.events);
const memberships = computed(() => props.memberships);


const calendarOptions = ref({
  plugins: [dayGridPlugin, interactionPlugin],
  initialView: 'dayGridMonth',
  events: events,
  eventClick: function(info:EventClickArg) {
    // Redirige al hacer click
    window.location.href = info.event.url
    info.jsEvent.preventDefault()
  }
});

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
                    <h2 class="font-bold text-5xl md:text-6xl lg:text-8xl text-white text-center slider animate-fade-slide-left">Eleva tu juego al siguiente <span class="text-secondary">nivel</span></h2>
                    <p class="text-white text-xl md:text-2xl lg:text-4xl font-medium text-center">Eleva el juego al siguiente nivel</p>
                    <Button class="py-8 px-16 text-white rounded-sm text-md md:text-xl lg:text-2xl font-medium bg-primary hover:bg-secondary hover:text-primary transition duration-300 ease-out hover:shadow-lg animate-fade-slide-up ">
                        <Link href="/torneos" class="flex items-center justify-center gap-4">¡Reserva ahora! <MoveRight/></Link>
                    </Button>
                </div>
            </div>

        </section>

        <section class="bg-white p-20 text-center">
            <HomeTitleSection title="¿Quiénes somos?" text="Somos un club privado que combina deporte, convivencia y bienestar. Creamos torneos de pádel a la medida y actividades sociales exclusivas."/>

            <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5 mt-10">
                <div class="flex flex-col items-center gap-5">
                    <div class="rounded-full bg-primary size-30 flex items-center justify-center text-white"><Trophy :size="60"/></div>
                    <p class="text-2xl font-home font-bold">Torneos personalizados</p>
                </div>

                <div class="flex flex-col items-center gap-5">
                    <div class="rounded-full bg-primary size-30 flex items-center justify-center text-white"><GraduationCap :size="60"/></div>
                    <p class="text-2xl font-home font-bold">Clases para todos los niveles</p>
                </div>

                <div class="flex flex-col items-center gap-5">
                    <div class="rounded-full bg-primary size-30 flex items-center justify-center text-white"><Users :size="60"/></div>
                    <p class="text-2xl font-home font-bold">Eventos sociales y deportivos</p>
                </div>
            </div>

        </section>

        <section class="bg-primary flex flex-col md:flex-row">
            <img src="@/assets/why-us.jpg" class="h-full w-full md:w-1/2">

            <div class="flex items-start justify-center flex-col font-home text-white py-10 px-5">

                    <h2 class="lg:text-7xl md:text-6xl text-3xl font-home font-bold uppercase text-secondary">Creamos experiencias significativas y personalizadas</h2>
                    <p  class="font-home lg:text-xl text-md mt-4 text-left">Al terminar tu sesión de padel podrás relajarte en nuestras instalaciones</p>

                    <div class="mt-10">

                        <div class="flex items-center justify-start gap-10 mt-10">
                            <div class="rounded-full bg-secondary size-18 flex items-center justify-center text-primary"><FlameKindling :size="40"/></div>
                            
                            <div>
                                <h4 class="text-2xl font-black text-secondary">Area de grill</h4>
                                <p class="text-lg">
                                    Contamos con un espacio diseñado para disfrutar momentos inolvidables alrededor del fuego. 
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center justify-start gap-10 mt-10">
                            <div class="rounded-full bg-secondary size-18 flex items-center justify-center text-primary"><WavesLadder :size="40"/></div>
                            
                            <div>
                                <h4 class="text-2xl font-black text-secondary">Alberca y jacuzzi</h4>
                                <p class="text-lg">
                                    Exclusiva alberca climatizada y relajante jacuzzi, ideales para disfrutar en cualquier época del año
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center justify-start gap-10 mt-10">
                            <div class="rounded-full bg-secondary size-18 flex items-center justify-center text-primary"><Thermometer :size="40"/></div>
                            
                            <div>
                                <h4 class="text-2xl font-black text-secondary">Sauna</h4>
                                <p class="text-lg">
                                    Relájate y renueva tu energía en un ambiente diseñado para tu bienestar.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center justify-start gap-10 mt-10">
                            <div class="rounded-full bg-secondary size-18 flex items-center justify-center text-primary"><ShowerHead :size="40"/></div>
                            
                            <div>
                                <h4 class="text-2xl font-black text-secondary">regaderas</h4>
                                <p class="text-lg">
                                    Frescura y confort después de cada partido o sesión en el club.
                                </p>
                            </div>
                        </div>

                    </div>
            </div>

        </section>

        <section class="p-20">
            <HomeTitleSection 
                title="Tipo de torneos" 
                subtitle="Torneos únicos, pensados para cada tipo de jugador"
            />

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 mt-5 w-9/10 m-auto gap-10">
                <div class="bg-black p-10 flex flex-col items-center gap-5">
                    <div class="rounded-full bg-primary size-25 flex items-center justify-center text-secondary hover:rotate-20 hover:scale-105 transition"><Trophy :size="50"/></div>
                    <h4 class="text-3xl text-secondary text-center font-home font-bold">Torneo básico</h4>
                    <p class="text-white text-center text-xl font-home font-bold">Puede formarse desde 4 hasta 8 equipos, con una duración de 3 a 5 horas.</p>
                </div>

                <div class="bg-black p-10 flex flex-col items-center gap-5">
                    <div class="rounded-full bg-primary size-25 flex items-center justify-center text-secondary hover:rotate-20 hover:scale-105 transition"><svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-venus-icon lucide-venus">
                        <path d="M12 15v7"/><path d="M9 19h6"/><circle cx="12" cy="9" r="6"/></svg>
                    </div>
                    <h4 class="text-3xl text-secondary text-center font-home font-bold">Torneo femenil</h4>
                    <p class="text-white text-center text-xl font-home font-bold">Puede formarse desde 4 hasta 8 equipos, con una duración de 3 a 5 horas.</p>
                </div>

                <div class="bg-black p-10 flex flex-col items-center gap-5">
                    <div class="rounded-full bg-primary size-25 flex items-center justify-center text-secondary hover:rotate-20 hover:scale-105 transition"><Sun :size="50"/></div>
                    <h4 class="text-3xl text-secondary text-center font-home font-bold">Premium Day Pass</h4>
                    <p class="text-white text-center text-xl font-home font-bold">Disfruta de un día de pádel, relájate en nuestras instalaciones.</p>
                </div>
            </div>

        </section>

        <section class="p-20 bg-primary">
            <h2 class="lg:text-7xl md:text-6xl text-5xl font-home font-bold uppercase text-center text-secondary">Modalidades de torneo</h2>
            <h3 class="font-home text-white text-center text-3xl mt-4">Disfruta de las diferentes formas de jugar en Lomas padel</h3>

            <Accordion type="single" class="w-full md:w-7/10 m-auto" collapsible :default-value="defaultValue">
                <AccordionItem v-for="item in accordionItems" :key="item.value" :value="item.value">
                <AccordionTrigger class="text-secondary text-2xl font-home font-bold">{{ item.title }}</AccordionTrigger>
                <AccordionContent class="text-white text-lg">
                    {{ item.content }}
                </AccordionContent>
                </AccordionItem>
            </Accordion>


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
            />

            <Swiper
                :modules="[Pagination]"
                :grab-cursor="true"
                :slides-per-view="3"
                :spaceBetween="20"
                :loop="true"
                :autoplay="{
                    delay: 3000
                }"
                :pagination="{ 
                    el: '.swiper-pagination',
                    clickable: true
                }"
                :breakpoints="{
                    320:  { slidesPerView: 1, spaceBetween: 5 },
                    768:  { slidesPerView: 2, spaceBetween: 10 },
                    1024: { slidesPerView: 3, spaceBetween: 20 },
                }"
                class="w-9/10 mx-auto mt-10"
            >
                <SwiperSlide
                    v-for="(membership, i) in memberships"
                    :key="membership.id"
                    >
                    <div class="bg-primary p-12 min-h-100 shadow-2xl flex flex-col items-center justify-between text-center w-9/10 m-auto rounded-sm">
                        <h3 class="text-5xl font-bold font-home mb-4 text-center text-secondary">{{ membership.name }}</h3>
                        <p class="text-white mb-6 font-home text-xl">{{ membership.description }}</p>
                        <div class="flex flex-col items-center justify-center gap-5 h-full">
                            <span class="text-3xl font-bold text-secondary"> $ {{ membership.price }}</span>
                            <Button class="bg-secondary hover:bg-[#cff082] text-primary px-6 py-3 transition text-2xl font-home font-bold" size="lg">
                                <Link :href="`/checkout/membership/${membership.id}`">Elegir</Link>
                            </Button>
                        </div>
                    </div>
                </SwiperSlide>
                <div class="swiper-pagination mt-30"></div>
            </Swiper>
            
            
        </section>
        
        <section class="bg-black p-20">
            <h2 class="lg:text-7xl md:text-6xl text-4xl font-home font-bold uppercase text-center text-white">Siguenos en <span class="text-secondary">#lomas_padel</span></h2>        
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