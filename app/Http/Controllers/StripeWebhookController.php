<?php

namespace App\Http\Controllers;

use App\Enums\AdminTypes;
use App\Mail\PaymentSuccessMail;
use App\Models\Memberships;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Webhook;
use App\Models\Places;
use App\Models\Payments;
use App\Models\Subscriptions;
use App\Models\User;
use App\PDF\ComprobantePago;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use Stripe\Charge;

class StripeWebhookController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            Log::error('Invalid payload');
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            Log::error('Invalid signature');
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Manejar eventos relevantes
        $type = $event->type;
        $data = $event->data->object;

        if ($type === 'payment_intent.succeeded') {
            $paymentIntent = $data;            

            //obtenemos los datos enviados del metadata
            $metadata = $paymentIntent->metadata ?? null;

            $id_place = $metadata['place_id'] ?? null;
            $membership_id = $metadata['membership_id'] ?? null;
            $productType = $metadata['product_type'] ?? null;
            
            //obtenemos los datos enviados del billing details
            $latestChargeID = $paymentIntent->latest_charge;
            $billingDetails = null;

            //si tengo el ID del latest charge, consulto a Stripe
            if($latestChargeID){
                $charge = Charge::retrieve($latestChargeID);
                $billingDetails = $charge->billing_details??null;

                if($billingDetails)
                    Log::info('Billing details configured:)');
                else
                    Log::warning('Billing details no found :(');
            }else{
                Log::warning('Latest charge no found :(');
            }

            $email = $billingDetails->email;
            $name = $billingDetails->name;
            $phone = $billingDetails->phone;

            if (!$email) {
                Log::warning("No se encontró email en billing_details para PaymentIntent {$paymentIntent->id}");
                return response()->json(['error' => 'No billing email'], 400);
            }

            $user = User::firstOrCreate(
                ['email' => $email],
                
                [
                    'name' => $name, 
                    'phone'=>$phone,
                    'role' => AdminTypes::CLIENT,
                    'password' => Str::password()
                ]
            );

            switch($productType){
                case 'tournament':
                    $place = Places::findOrFail($id_place);

                    $payment = Payments::firstOrCreate(
                        ['id_transaction' => $paymentIntent->id],
                        [
                            'amount' => $paymentIntent->amount / 100,
                            'currency' => $paymentIntent->currency,
                            'method' => $paymentIntent->payment_method_types[0] ?? 'card',
                            'status' => $paymentIntent->status,
                        ]
                    );
                    
                    $place->update([
                        'id_user' => $user->id,
                        'id_payment' => $payment->id,
                        'status' => 'ocupado',
                    ]);

                    $pdf = new ComprobantePago($user, $payment, 'tournament', $place);
                    $pdfData = $pdf->generar();
                
                    Log::info("Stripe payment succeeded: {$paymentIntent->id}, place: {$place->id}, user: {$user->id}");

                    Mail::to($user->email)
                    ->send(
                        (new PaymentSuccessMail($user, $payment, 'tournament', $place))
                        ->attachData($pdfData, 'comprobante de pago.pdf')
                    );

                    return response()->json(['received' => true], 200);
                break;

                case 'membership':
                    $membership = Memberships::findOrFail($membership_id);

                    $expired_at = Carbon::now()->addMonths($membership->months_to_expire)->toDateTimeString();

                    $payment = Payments::firstOrCreate(
                        ['id_transaction' => $paymentIntent->id],
                        [
                            'amount' => $paymentIntent->amount / 100,
                            'currency' => $paymentIntent->currency,
                            'method' => $paymentIntent->payment_method_types[0] ?? 'card',
                            'status' => $paymentIntent->status,
                        ]
                    );

                    Subscriptions::create([
                        'id_user' =>$user->id,
                        'id_payment' => $payment->id,
                        'id_membership' => $membership_id,
                        'expired_at'=>$expired_at
                    ]); 

                    $pdf = new ComprobantePago($user, $payment, 'membership', $membership);
                    $pdfData = $pdf->generar();

                    Log::info("Stripe payment succeeded: {$paymentIntent->id}, membership: {$membership->id}, user: {$user->id}");
                    Mail::to($user->email)
                    ->send(
                        (new PaymentSuccessMail($user, $payment, 'membership', $membership))
                        ->attachData($pdfData, 'comprobante de pago.pdf')
                    );

                    return response()->json(['received' => true], 200);
                break;

                default:
                    Log::warning("No se encontró email en billing_details para PaymentIntent {$paymentIntent->id}");
                    return response()->json(['error' => 'No valid product type'], 400);
            }            
        }

        // Manejar fallo de pago u otros eventos si quieres
        if ($type === 'payment_intent.payment_failed') {
            $paymentIntent = $data;
            $metadata = $paymentIntent->metadata ?? null;
            $placeId = $metadata['place_id'] ?? null;

            if ($placeId) {
                $place = Places::find($placeId);
                if ($place) {
                    $place->status = 'libre';
                    $place->save();
                }
            }

            Log::warning("Stripe payment failed: {$paymentIntent->id}");
            return response()->json(['received' => true], 200);
        }

        Log::info("Evento {$type} recibido, evento ignorado");
        return response()->json(['received' => true], 200);
    }
}
