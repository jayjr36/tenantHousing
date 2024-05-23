<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function addMoney(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        $user = auth()->user(); // Get the authenticated user

        // Update the user's wallet balance
        $user->wallet += $request->amount;
        $user->save();

        return redirect()->back()->with('success', 'Amount added to wallet successfully.');
    }
}
