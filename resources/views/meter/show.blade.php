@extends('layouts.app')

@section('content')
<div class="container-fluid bg-dark text-white mt-3" style="height: 130vh">
    <h1 class="mb-4 text-center">Meter Readings</h1>
    
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-7">
    
                <div class="d-flex flex-wrap">
                    @foreach ($meters as $meter)
                        <div class="card mb-4 mx-2">
                            <div class="card-body border-white">
                                <h5 class="card-title">Meter Number: {{ $meter->meter_number }}</h5>
                                <p class="card-text">Channel 1 Units: {{ $meter->channel1_units }}</p>
                                <p class="card-text">Channel 2 Units: {{ $meter->channel2_units }}</p>
                                <p class="card-text">Channel 3 Units: {{ $meter->channel3_units }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    
    

    @if(session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger" role="alert">
        {{ session('error') }}
    </div>
    @endif

    <div class="container mt-5">
        <div class="row justify-content-center">
            <h4 .text-center>Purchase Electricity</h4>
            <div class="col-md-7">
                <p>Wallet Balance: {{ $user->wallet }} TSH</p>
                <form action="{{ route('addUnits') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="meterNumber" class="form-label">Meter Number</label>
                        <input type="text" name="meterNumber" id="meterNumber" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="channel_number" class="form-label">Channel Number:</label>
                        <select name="channel_number" id="channel_number" class="form-select" aria-label="Channel Number" required>
                            <option value="1">Channel 1</option>
                            <option value="2">Channel 2</option>
                            <option value="3">Channel 3</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount (TSH):</label>
                        <input type="number" name="amount" id="amount" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">SUBMIT</button>
                </form>
            </div>
        </div>
    </div>
    
</div>
@endsection
