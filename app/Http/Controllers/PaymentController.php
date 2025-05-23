<?php

namespace App\Http\Controllers;

use Midtrans\Snap;
use App\Models\Fund;
use App\Services\MidtransService;
use Exception;
use Midtrans\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller {
    // public function createTransaction(Request $request) {
    //     try {
    //         Config::$serverKey = config('services.midtrans.server_key');
    //         Config::$isProduction = config('services.midtrans.is_production');
    //         Config::$isSanitized = true;
    //         Config::$is3ds = true;

    //         $request->validate([
    //             'amount' => 'required|numeric|min:1000',
    //             'name' => 'required|string',
    //             'email' => 'required|email',
    //         ]);

    //         $params = [
    //             'transaction_details' => [
    //                 'order_id' => 'ORDER-' . uniqid(),
    //                 'gross_amount' => $request->amount,
    //             ],
    //             'customer_details' => [
    //                 'first_name' => $request->name,
    //                 'email' => $request->email,
    //             ],
    //         ];

    //         $snapToken = Snap::getSnapToken($params);

    //         return response()->json(['token' => $snapToken]);
    //     } catch (\Exception $e) {
    //         \Log::error('MidTrans Error: ' . $e->getMessage());
    //         return response()->json(['error' => 'Failed to create transaction'], 500);
    //     }
    // }

    /**
     * Show Snap UI Page.
     *
     * @return redirect
     */
    public function show(Fund $fund) {
        $user = Auth::user();
        if ($user->id !== $fund->donorDonation->donor->id) {
            Abort(403, 'Forbidden');
        }

        return redirect($fund->redirect_url);
    }

    /**
     *
     */
    public function finish(Request $request, MidtransService $service) {
        $validated = $request->validate([
            "order_id" => "bail|required|string",
            "status_code" => "bail|required",
            "transaction_status" => "bail|required"
        ]);

        try {
            $service->finishPayment($validated);
        } catch (Exception $e) {
            abort(500, $e->getMessage());
        }

        $orderId = data_get($validated, 'order_id');
        $fund = Fund::where('order_id', $orderId)->first();
        $donation = $fund->donation;
        return redirect()->route('donations.show', $donation);
    }

    /**
     *
     */
    public function error(Request $request, MidtransService $service) {
        $validated = $request->validate([
            "order_id" => "bail|required|string",
            "status_code" => "bail|required",
            "transaction_status" => "bail|required"
        ]);

        try {
            $service->errorPayment($validated);
        } catch (Exception $e) {
            abort(500, $e->getMessage());
        }

        $orderId = data_get($validated, 'order_id');
        $fund = Fund::where('order_id', $orderId)->first();
        $donation = $fund->donation;
        return redirect()->route('donations.show', $donation);
    }
}
