<script lang="ts">
import { defineComponent, ref, onMounted, computed } from 'vue';
import { loadStripe, Stripe, StripeElements } from '@stripe/stripe-js';
import { usePage } from '@inertiajs/vue3';
import { HomePageName, PublicAppLayout } from '@/components/home';
import { AppPageProps, Place, Tournament } from '@/types';

interface CheckoutPageProps extends AppPageProps {
  place: Place;
  tournament: Tournament;
  stripeKey: string;
  clientSecret: string;
}

const { props } = usePage<CheckoutPageProps>();
const place = computed(() => props.place);
const tournament = computed(() => props.tournament);
const stripeKey = computed(() => props.stripeKey);
const clientSecret = computed(() => props.clientSecret);

const name = ref('Joel Alejandro Ibarra Villar');
const email = ref('joelvillar35@gmail.com');
const message = ref('');
const error = ref(false);
const processing = ref(false);

export default defineComponent({
  name: 'CheckoutPage',
  components: { HomePageName, PublicAppLayout },
  setup() {
    const { props } = usePage<CheckoutPageProps>();
    const place = computed(() => props.place);
    const tournament = computed(() => props.tournament);
    const stripeKey = computed(() => props.stripeKey);
    const clientSecret = computed(() => props.clientSecret);

    const name = ref('Joel Alejandro Ibarra Villar');
    const email = ref('joelvillar35@gmail.com');
    const message = ref('');
    const error = ref(false);
    const processing = ref(false);

    let stripe: Stripe | null = null;
    let elements: StripeElements | null = null;

    const tournamentValue = tournament;

    onMounted(async () => {
      if (!stripeKey.value) {
        message.value = 'Clave pública de Stripe no configurada.';
        error.value = true;
        return;
      }
      stripe = await loadStripe(stripeKey.value);
      if (!stripe) {
        message.value = 'Error cargando Stripe.';
        error.value = true;
        return;
      }

      // Inicializar elements con clientSecret
      if (clientSecret.value) {
        elements = stripe.elements({ 
          clientSecret: clientSecret.value 
        });
        const paymentElement = elements.create('payment');
        paymentElement.mount('#payment-element');
      } else {
        message.value = 'No se recibió client_secret.';
        error.value = true;
      }
    });

    const handleSubmit = async () => {
      if (!stripe || !elements) {
        message.value = 'Stripe no inicializado';
        error.value = true;
        return;
      }

      // Validar formulario simple
      if (!name.value || !email.value) {
        message.value = 'Nombre y correo obligatorios';
        error.value = true;
        return;
      }

      processing.value = true;
      message.value = '';
      error.value = false;

      try {
        // Confirmar pago usando Payment Element, pasando billing details
        const { error: stripeError } = await stripe.confirmPayment({
          elements,
          confirmParams: {
            // No hacemos redirect: confirmPayment intentará completar inline si es posible
            return_url: window.location.href, // fallback; algunos métodos redirigen
            payment_method_data: {
              billing_details: {
                name: name.value,
                email: email.value,
              },
            },
          },
        } as any);

        if (stripeError) {
          // Si falla, mostrar error
          message.value = stripeError.message || 'Error al procesar el pago';
          error.value = true;
          processing.value = false;
          return;
        }

        // Si no hay error inmediato, la confirmación puede redirigir o el webhook marcará el pago.
        message.value = 'Pago procesándose. Si la transacción se completa, recibirás confirmación. (Stripe Webhook gestionará la actualización del place)';
        error.value = false;
        processing.value = false;
      } catch (e: any) {
        message.value = e?.message ?? 'Error inesperado';
        error.value = true;
        processing.value = false;
      }
    };

    return {
      place,
      tournament: tournamentValue,
      name,
      email,
      message,
      error,
      processing,
      handleSubmit,
    };
  },
});
</script>


<template>
  <PublicAppLayout>
    <HomePageName title="Terminar pago" />

    <div class="max-w-2xl mx-auto p-4">
      <h2 class="text-lg font-semibold mb-4">Pago - Lugar del torneo</h2>

      <div class="bg-white shadow-md rounded p-6">
        <form @submit.prevent="handleSubmit">
          <div class="mb-4">
            <label class="block text-sm font-medium">Nombre</label>
            <input v-model="name" required type="text" class="mt-1 block w-full rounded border-gray-300 p-2" />
          </div>

          <div class="mb-4">
            <label class="block text-sm font-medium">Correo</label>
            <input v-model="email" required type="email" class="mt-1 block w-full rounded border-gray-300 p-2" />
          </div>

          <div class="mb-4">
            <label class="block text-sm font-medium">Torneo</label>
            <div class="mt-1">
              <div>Torneo #{{tournament?.id ?? '??' }} — {{ tournament?.admission_price ?? 0 }} MXN</div>
            </div>
          </div>

          <div class="mb-4">
            <label class="block text-sm font-medium">Detalles de pago</label>
            <div id="payment-element" class="mt-2"></div>
          </div>

          <div v-if="message" class="mb-4 text-sm" :class="{'text-red-600': error, 'text-green-600': !error}">
            {{ message }}
          </div>

          <button type="submit" :disabled="processing" class="px-4 py-2 rounded bg-blue-600 text-white">
            <span v-if="processing">Procesando...</span>
            <span v-else>Pagar {{ tournament?.admission_price ?? '' }} MXN</span>
          </button>
        </form>
      </div>
    </div>
  </PublicAppLayout>
</template>