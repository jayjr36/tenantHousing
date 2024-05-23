<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeterReading extends Model
{
    use HasFactory;
    
    protected $fillable = ['meter_number', 'channel1_units', 'channel2_units', 'channel3_units'];

}
