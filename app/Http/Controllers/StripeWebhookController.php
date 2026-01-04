<?php

namespace App\Http\Controllers;

use App\Models\StripePayment;
use Illuminate\Http\Request;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
            if ($event->type == 'checkout.session.completed') {
                $session = $event->data->object;
                $customerEmail = $session->customer_details->email ?? null;
                $customerName = $session->customer_details->name ?? null;
                $amountTotal = $session->amount_total / 100; // amount in AED
                $paymentStatus = $session->payment_status;
                $clientReferenceId = $session->client_reference_id ?? null;

                $stripePayment = new StripePayment();
                $stripePayment->name = $customerName;
                $stripePayment->email = $customerEmail;
                $stripePayment->payment_status = $paymentStatus;
                $stripePayment->client_reference_id = $clientReferenceId;
                $stripePayment->amount_total = $amountTotal;
                $stripePayment->save();
            }
            return response()->json(['success' => true,'status' => $event->type]);

        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response()->json(['error' => 'Invalid signature'], 400);
        } catch (\Exception $e) {
            // Some other error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
