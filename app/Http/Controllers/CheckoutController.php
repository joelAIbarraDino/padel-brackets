<?php

namespace App\Http\Controllers;

use App\Models\Memberships;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Stripe\StripeClient;
use App\Models\Places;
use App\Models\Tournament;

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
        return Inertia::render('checkout/checkoutPlace', [
            'place' => $placeModel,
            'tournament' => $tournament,
            'clientSecret' => $paymentIntent->client_secret,
        ]);
    }

    public function showMembershipCheckout(Request $request,Memberships $membership)
    {
        
        //si el precio es incorrecto, se redirige para evitar errores
        if($membership->price < 0)
            return redirect('/');

        // Determinar el precio en centavos MXN
        $amount = (float) $membership->price;
        $amountCents = (int) round($amount * 100);

        // Crear PaymentIntent con métodos automáticos
        $paymentIntent = $this->stripe->paymentIntents->create([
            'amount' => $amountCents,
            'currency' => 'mxn',
            'automatic_payment_methods' => ['enabled' => true],
            // metadata para referencia del webhook
            'metadata' => [
                'membership_id' => $membership->id,
                'product_type' => 'membership',
            ]
        ]);

        // Pasar props a la vista Inertia
        return Inertia::render('checkout/checkoutMembership', [
            'membership' => $membership,
            'clientSecret' => $paymentIntent->client_secret,
        ]);
    }

    public function successPlace($place)
    {

        $placeModel = Places::with('tournament')->findOrFail($place);
        $tournament = $placeModel->tournament;

        return Inertia::render('checkout/successPlace',[
            'place' => $placeModel,
            'tournament' => $tournament,
        ]);

    }

    public function successMembership(Memberships $membership)
    {
        return Inertia::render('checkout/successMembership',[
            'membership' => $membership,
        ]);
    }
}
