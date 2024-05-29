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

public function updateMeterData(Request $request, $meterNumber)
{
    // Retrieve the existing meter data based on the meter number
    $meterData = DB::table('meter_readings')
        ->select('meter_number', 'channel1_units', 'channel2_units', 'channel3_units')
        ->where('meter_number', $meterNumber)
        ->first();

    // Check if meter data exists
    if ($meterData) {
        // Get additional units from the API request
        $additionalChannel1Units = $request->input('channel1_units', 0);
        $additionalChannel2Units = $request->input('channel2_units', 0);
        $additionalChannel3Units = $request->input('channel3_units', 0);

        // Add the additional units to the existing units
        $totalChannel1Units = $meterData->channel1_units + $additionalChannel1Units;
        $totalChannel2Units = $meterData->channel2_units + $additionalChannel2Units;
        $totalChannel3Units = $meterData->channel3_units + $additionalChannel3Units;

        // Update the meter data with the new total units
        DB::table('meter_readings')
            ->where('meter_number', $meterNumber)
            ->update([
                'channel1_units' => $totalChannel1Units,
                'channel2_units' => $totalChannel2Units,
                'channel3_units' => $totalChannel3Units,
            ]);

        // Prepare the response data
        $updatedMeterData = [
            'meter_number' => $meterData->meter_number,
            'channel1_units' => $totalChannel1Units,
            'channel2_units' => $totalChannel2Units,
            'channel3_units' => $totalChannel3Units,
        ];

        // Return the response with the updated total units
        return response()->json($updatedMeterData);
    } else {
        // Return an error response if the meter number is not found
        return response()->json(['error' => 'Meter number not found'], 404);
    }
}


}
