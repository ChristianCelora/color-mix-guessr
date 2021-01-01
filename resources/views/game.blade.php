@extends('layouts.app')

@section('styles')
<link href="{{ asset('css/game.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        {{-- debug data --}}
        {{-- @php echo(print_r($data)) @endphp --}}
        @isset($data)
        <div class="col-12 text-center">
            <h2>Color {{$data["step_number"]}}</h2>
        </div>
        <div class="col-12 row">
            <div class="col-6">
                {{-- Color picker --}}
                {{-- Timer --}}
                {{-- Score --}}
            </div>
            <div class="col-6">
                {{-- Input colors --}}
                <div>
                    @foreach ($data["input_colors"] as $color)
                        <div class="d-flex">
                            <div class="d-inline">
                                <div>{{$color['name']}}</div>
                                <div class="input-color" style="background-color: #{{$color['hex']}}"></div>
                            </div>
                            <div class="d-inline input-weight">50%</div>
                        </div>
                    @endforeach
                </div>
                {{-- Mixing gif --}}
                {{-- Solution --}}
            </div>
        </div>
        @endisset
    </div>
</div>
@endsection