<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MeterReading;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class MeterController extends Controller
{

    public function show()
{
    $meters = MeterReading::all();
    $user = Auth::user(); 

    return view('meter.show', compact('meters', 'user'));
}

//     public function show($meterNumber)
// {
//     $meter = MeterReading::where('meter_number', $meterNumber)->firstOrFail();
//    // $meter = MeterReading::where('meter_number', $meterNumber)->firstOrFail();
//     $user = Auth::user(); 

//     return view('meter.show', compact('meter', 'user'));
// }

public function addUnits(Request $request)
{
    $request->validate([
        'meterNumber' => 'required', // Assuming you want to validate meterNumber separately
        'channel_number' => 'required|in:1,2,3',
        'amount' => 'required|numeric|min:0',
    ]);

    $user = Auth::user();
    $costPerUnit = 500;
    $unitsToAdd = $request->amount / $costPerUnit;

    $meter = MeterReading::where('meter_number', $request->meterNumber)->firstOrFail(); // Check if the meter exists

    if ($user->wallet >= $request->amount) {
        $user->wallet -= $request->amount;
        $user->save();

        $channel = 'channel' . $request->channel_number . '_units';
        $meter->$channel += $unitsToAdd;
        $meter->save();

        return redirect()->back()->with('success', 'Units added successfully.');
    } else {
        return redirect()->back()->with('error', 'Insufficient funds.');
    }
}


}
