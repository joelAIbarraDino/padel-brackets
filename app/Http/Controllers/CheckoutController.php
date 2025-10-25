<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Stripe\StripeClient;
use App\Models\Places;

class CheckoutController extends Controller
{
    protected StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(env('STRIPE_SECRET'));
    }

    public function showTournamentCheckout(Request $request, $place)
    {
        // Cargar place y tournament relacionado
        $placeModel = Places::with('tournament')->findOrFail($place);
        $tournament = $placeModel->tournament;

        //si el lugar ya fue tomado, redirigo para evitar sobrecomprar el mismo lugar
        if($placeModel->status != 'libre'){
            return redirect('/');
        }

        //si el precio es incorrecto, se redirige para evitar errores
        if($tournament->admission_price < 0)
            return redirect('/');

        // Determinar el precio en centavos MXN
        $amount = (float) $tournament->admission_price;
        $amountCents = (int) round($amount * 100);

        // Crear PaymentIntent con métodos automáticos
        $paymentIntent = $this->stripe->paymentIntents->create([
            'amount' => $amountCents,
            'currency' => 'mxn',
            'automatic_payment_methods' => ['enabled' => true],
            // metadata para referencia del webhook
            'metadata' => [
                'place_id' => $placeModel->id,
                'product_type' => 'tournament',
                'tournament_id' => $tournament->id,
            ]
        ]);

        // Pasar props a la vista Inertia
        return Inertia::render('checkout/index', [
            'place' => $placeModel,
            'tournament' => $tournament,
            'clientSecret' => $paymentIntent->client_secret,
        ]);
    }

    /**
     * Mostrar checkout para membership (por ahora "no implementadas")
     */
    public function showMembershipCheckout(Request $request, $id)
    {
        // por ahora devolvemos una página similar; marca como 'not implemented'
        $dummyAmount = 0.01; // placeholder
        $amountCents = (int) round($dummyAmount * 100);

        $paymentIntent = $this->stripe->paymentIntents->create([
            'amount' => $amountCents,
            'currency' => 'mxn',
            'automatic_payment_methods' => ['enabled' => true],
            'metadata' => [
                'product_type' => 'membership',
                'membership_id' => $id,
            ],
        ]);

        // En este caso no hay place ni tournament
        return Inertia::render('Checkout/MembershipCheckoutPage', [
            'stripeKey' => env('STRIPE_KEY'),
            'clientSecret' => $paymentIntent->client_secret,
            'membershipId' => $id,
        ]);
    }
}
