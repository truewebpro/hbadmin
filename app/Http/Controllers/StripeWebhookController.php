<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\StripePayment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
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
//            if ($event->type == 'checkout.session.completed') {
//                $session = $event->data->object;
//                $customerEmail = $session->customer_details->email ?? null;
//                $customerName = $session->customer_details->name ?? null;
//                $amountTotal = $session->amount_total / 100; // amount in AED
//                $paymentStatus = $session->payment_status;
//                $clientReferenceId = $session->client_reference_id ?? null;
//
//                $stripePayment = new StripePayment();
//                $stripePayment->name = $customerName;
//                $stripePayment->email = $customerEmail;
//                $stripePayment->payment_status = $paymentStatus;
//                $stripePayment->client_reference_id = $clientReferenceId;
//                $stripePayment->amount_total = $amountTotal;
//                $stripePayment->save();
//            }
//            return response()->json(['success' => true,'status' => $event->type]);

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
        if($event->type === 'invoice.payment_succeeded'){
            $invoice = $event->data->object;
            $subscriptionId = $invoice->subscription;
            $customerId = $invoice->customer;
            Stripe::setApiKey(config('services.stripe.secret'));
            $subscription = \Stripe\Subscription::retrieve($subscriptionId);

            $meta = $subscription->metadata ?? null;
            $userId = $meta->userId ?? null;
            $packageName = $meta->packageName ?? null;
            $priceId = $meta->priceId ?? null;
            if ($userId && $packageName && $priceId) {
                $user = User::find($userId);
                if($user){
                    Payment::create([
                        'user_id' => $userId,
                        'package_tier' => $packageName,
                        'stripe_customer_id' => $customerId,
                        'stripe_subscription_id' => $subscriptionId,
                        'stripe_price_id' => $priceId,
                    ]);
                    $user->update([
                        'package_tier' => $packageName,
                    ]);
                }
            }
        }
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                Log::info("Checkout completed", ['session' => $session]);
                // Save order/payment in DB
                break;

            case 'invoice.payment_succeeded':
                $invoice = $event->data->object;
                Log::info("Invoice payment succeeded", ['invoice' => $invoice]);
                // Mark subscription/transaction as paid
                break;

            case 'customer.subscription.deleted':
                $subscription = $event->data->object;
                Log::info("Subscription cancelled", ['subscription' => $subscription]);
                // Handle cancellation
                break;

            default:
                Log::info("Unhandled Stripe event", ['type' => $event->type]);
                Log::info("Event data", $event->data->toArray());
        }

        return response()->json(['status' => 'success']);
    }
}
