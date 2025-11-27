<script setup lang="ts">
import { HomePageName, PublicAppLayout } from '@/components/home';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import InputError from '@/components/InputError.vue';
import { computed, onMounted, ref } from 'vue';
import { AppPageProps, Place, Tournament } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { loadStripe, Stripe, StripeElements } from '@stripe/stripe-js';

function formatDateTime(dateString:string) {
  if (!dateString) return 'No definida'

  const date = new Date(dateString)

  return date.toLocaleString('es-MX', {
    day: '2-digit',
    month: 'long',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
    hour12: true,
  })
}

//proms para los detalles de la compra
interface CheckoutPageProps extends AppPageProps{
    place: Place;
    tournament: Tournament;
    clientSecret:string;
}

//proms del backend
const {props} = usePage<CheckoutPageProps>();
const place = computed(() => props.place);
const tournament = computed(() => props.tournament);
const clientSecret = computed(() => props.clientSecret);

const user = props.auth.user;

//stripe key expuesto desde las variables de entorno del proyecto
const stripeKey = import.meta.env.VITE_STRIPE_KEY;

//variables de stripe
const stripe = ref<Stripe|null>(null);
const elements = ref<StripeElements | null>(null);

//cargo despues de que aparezca mi pagina los elementos de pago
onMounted(async () =>{
    stripe.value = await loadStripe(stripeKey);

    //valido si la configuración de las credenciales de stripe son correctas
    if(!stripe.value || !clientSecret.value){
        console.error('Error al inicializar Stripe');
        return;
    }

    //inicializo el elemento para mostrar los datos de pago requeridos en el form
    elements.value = stripe.value.elements({
        clientSecret:clientSecret.value,
        appearance:{
            theme: 'stripe',
            variables:{
                colorPrimary:'#4F46E5',
                colorBackground:'#fff',
                colorText:'#000',
                borderRadius:'8px',
                fontFamily: 'Inter, sans-serif',
            }
        }
    });

    const paymentElement = elements.value.create('payment', {
        layout:{
            type: 'tabs'
        }
    });

    paymentElement.mount('#payment-element');

});

//datos iniciales del formulario
const name = ref(user?.name??'');
const nameError = ref('');

const email = ref(user?.email??'');
const emailError = ref('');

const phone = ref(user?.phone??'');
const phoneError = ref('');

const message = ref('');
const messageInput = ref('');
const error = ref (false);

//funcion para enviar los datos y empezar el proceso de pago
const handle = async () =>{
    if(!stripe.value || !elements.value){
        console.error('Error al inicializar Stripe');
        return;
    }

    //validacion de formulario
    if(!name.value){
        nameError.value = "Debe ingresar el nombre";
        message.value="Verifique los datos ingresados para continuar";
        error.value = true;
        return;
    }

    if(!email.value){
        emailError.value = "Debe ingresar el correo";
        message.value="Verifique los datos ingresados para continuar";
        error.value = true;
        return;
    }

    if(!phone.value){
        phoneError.value = "Debe ingresar el telefono";
        message.value="Verifique los datos ingresados para continuar";
        error.value = true;
        return;
    }

    messageInput.value = "";
    message.value = "";
    nameError.value = "";
    emailError.value = "";
    phoneError.value = "";
    error.value = false;

    try{
        //confirmar el pago con los datos del formulario
        const {error:stripeError} = await stripe.value.confirmPayment({
            elements:elements.value,
            confirmParams:{
                return_url: `${window.location.origin}/checkout/tournament/success/${place.value.id}`,
                payment_method_data:{
                    billing_details:{
                        name:name.value,
                        email:email.value,
                        phone:phone.value
                    }
                }
            }

        } as any);

        if(stripeError){
            message.value = stripeError.message??'Error al procesar el pago';
            error.value = true;
        }else{
            message.value = "Procesando pago";
            error.value = false;
        }
    }catch(err:any){
        message.value = "Ocurrio un error al procesar el pago";
        error.value = true;
        console.error(err.message);
    }
}

</script>

<template>
    <PublicAppLayout>
        <HomePageName title="Completar pago"/>

        <div class="w-19/20 md:w-7/10 lg:w-1/2 border shadow-xl m-auto p-4 my-10 rounded-md">
            <p class="font-bold text-xl">Detalles de reservación</p>
            <hr class="mb-2">
            
            <div class="space-y-2">
                <p>Torneo: <span class="font-bold text-indigo-700">Torneo #{{ tournament?.id ?? '' }}</span> </p>
                <p>Fecha y hora del torneo: <span class="font-bold text-indigo-700">{{ formatDateTime(tournament?.scheduled_event)}}</span> </p>
                <p>Lugar: <span class="font-bold text-indigo-700">Lugar #{{ place.id ?? '' }}</span> </p>
                <hr>
                <p>Costo de entrada: <span class="font-bold text-indigo-700">${{ tournament.admission_price ?? '' }}</span> </p>
            </div>

        </div>
        
        <form 
            class="w-19/20 md:w-7/10 lg:w-1/2 shadow-2xl mx-auto p-6 m-4 border rounded-md space-y-4"
            @submit.prevent="handle"
        >
            
            <div class="space-y-2">
                <p class="text-sm text-neutral-500">Datos personales</p>
                <div class="flex gap-4 flex-col md:flex-row border p-4 rounded-lg">

                    <div class="grid gap-1 flex-1 space-y">
                        <Label for="name">Nombre de jugador</Label>
                        <Input
                            id="name"
                            type="text"
                            placeholder="John Doe"
                            v-model="name"
                            :disabled="!!user"
                        />
                        <InputError class="mt-1" :message="nameError" />
                    </div>
                </div>

                <div class="flex gap-4 flex-col md:flex-row border p-4 rounded-lg">
                    <div class="grid gap-1 flex-1 space-y">
                        <Label for="name">Teléfono</Label>
                        <Input
                            id="name"
                            type="tel"
                            placeholder="5514117400"
                            v-model="phone"
                            :disabled="!!user"
                        />
                        <InputError class="mt-1" :message="phoneError" />
                    </div>

                    <div class="grid gap-1 flex-1 space-y">
                        <Label for="email">Correo</Label>
                        <Input
                            id="email"
                            type="email"
                            placeholder="example@correo.com"
                            v-model="email"
                            :disabled="!!user"
                        />
                        <InputError class="mt-1" :message="emailError" />
                    </div>
                </div>
            </div>

            <div class="space-y-2">
                <p class="text-sm text-neutral-500">Detalles de pago</p>
                <div class="border p-4 rounded-lg">
                    <div id="payment-element"></div>
                </div>
            </div>

            <div>
                <p 
                    v-if="message"
                    class="mb-2 text-sm"  
                    :class="{'text-red-600': error, 'text-green-600': !error}"
                >{{message}}</p>

                <Button type="submit" size="lg" class="bg-indigo-700 hover:bg-indigo-600">Comprar ahora</Button>
            </div>

        </form>
    </PublicAppLayout>
</template>