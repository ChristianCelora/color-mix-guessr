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
                        <div class="d-inline-flex offset-1">
                            <div class="row">
                                <div class="col-8 justify-content-center">
                                    <div class="text-center">{{$color['name']}}</div>
                                    <div class="input-color" style="background-color: #{{$color['hex']}}"></div>
                                </div>
                                <div class="col-4 input-weight d-flex align-items-center">
                                    <div>50%</div>
                                </div>
                            </div>
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