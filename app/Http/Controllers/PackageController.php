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

    public function updateSubscription(Request $request)
    {
//        $user = auth()->user();
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
        Log::info("payment Detail",$payment);
        return response()->json([
            'success' => true,
            'message' => 'Subscription updated successfully',
            'payment' => $payment

        ]);
    }
}
