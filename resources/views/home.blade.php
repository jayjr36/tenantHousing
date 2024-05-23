@extends('layouts.app')

@section('content')
<div class="container-fluid bg-dark" style="height: 100vh">
    <div class="row justify-content-center">
        <div class="col-md-8 py-5">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('Welcome aboard! Manage your electricity consumption efficiently with our user-friendly meter reading and purchase system.') }}
                     <div class="text-center">
                        <a class="btn btn-info" href="{{route('meter.show')}}">CHECK OUT METER READINGS</a>

                     </div>

                                    </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('submitButton').addEventListener('click', function() {
        var meterNumber = document.getElementById('meterNumber').value;
        var formAction = "{{ route('meter.show', ['meterNumber' => '']) }}";
        formAction = formAction.replace('meterNumber', meterNumber);
        document.getElementById('meterForm').action = formAction;
        document.getElementById('meterForm').submit();
    });
</script>

@endsection
