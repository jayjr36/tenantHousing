<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MeterReading;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


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

public function updateMeterData(Request $request)
{
    $validatedData = $request->validate([
        'meter_number' => 'required|string',
        'channel1_units' => 'required|numeric',
        'channel2_units' => 'required|numeric',
        'channel3_units' => 'required|numeric',
    ]);

    $meterNumber = $validatedData['meter_number'];

    $meterData = DB::table('meter_readings')
        ->select('meter_number', 'channel1_units', 'channel2_units', 'channel3_units')
        ->where('meter_number', $meterNumber)
        ->first();

    if ($meterData) {
        $incomingChannel1Units = $validatedData['channel1_units'];
        $incomingChannel2Units = $validatedData['channel2_units'];
        $incomingChannel3Units = $validatedData['channel3_units'];

        $totalChannel1Units = $meterData->channel1_units - $incomingChannel1Units;
        $totalChannel2Units = $meterData->channel2_units - $incomingChannel2Units;
        $totalChannel3Units = $meterData->channel3_units - $incomingChannel3Units;

        DB::table('meter_readings')
            ->where('meter_number', $meterNumber)
            ->update([
                'channel1_units' => $totalChannel1Units,
                'channel2_units' => $totalChannel2Units,
                'channel3_units' => $totalChannel3Units,
            ]);

        $updatedMeterData = [
            'c1' => $totalChannel1Units,
            'c2' => $totalChannel2Units,
            'c3' => $totalChannel3Units,
        ];

        return response()->json($updatedMeterData);
    } else {
        return response()->json(['error' => 'Meter number not found', 'success'=> 'fail'], 404);
    }
}



}
