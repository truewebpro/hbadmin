<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PackageController extends Controller
{
    public function getPackages()
    {
        $packages = Package::get();
        return response()->json($packages);
    }

    public function completedSubscription(Request $request)
    {
        $payment = Payment::create([
            'user_id' => $request->get('user_id'),
            'package_tier' => $request->get('package_tier'),
            'stripe_customer_id' => $request->get('stripe_customer_id'),
            'stripe_subscription_id' => $request->get('stripe_subscription_id'),
            'stripe_price_id' => $request->get('stripe_price_id'),
        ]);

        $user = User::findOrFail($request['user_id']);
        $user->update([
            'package_tier' => $request['package_tier'] ?? 'standard',
            'package_expires_at' => now()->addMonth(), // or use Stripe data
        ]);


        Log::info("request Detail",$request->all());
        return response()->json([
            'success' => true,
            'message' => 'Subscription updated successfully',
            'payment' => $payment
        ]);
    }

    public function updateSubscription(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|integer',
            'package_tier' => 'required|string',
            'stripe_subscription_id' => 'required|string',
            'stripe_customer_id' => 'required|string',
            'stripe_price_id' => 'required|string',
            'status' => 'nullable|string',
            'current_period_end' => 'nullable|integer',
        ]);
        $payment = Payment::updateOrCreate(
            [
                'stripe_subscription_id' => $data['stripe_subscription_id'],
                'stripe_customer_id' => $data['stripe_customer_id'],
            ],
            [
                'user_id' => $data['user_id'],
                'package_tier' => $data['package_tier'],
                'stripe_price_id' => $data['stripe_price_id'],
            ]
        );
        $user = User::findOrFail($data['user_id']);
        $expiresAt = isset($data['current_period_end'])
            ? \Carbon\Carbon::createFromTimestamp($data['current_period_end'])
            : now()->addMonth();

        $user->update([
            'package_tier' => $data['package_tier'],
            'package_expires_at' => $expiresAt,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Subscription updated successfully',
            'payment' => $payment,
        ]);
    }
}
