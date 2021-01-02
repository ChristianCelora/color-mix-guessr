@extends('layouts.app')

@section('styles')
<link href="{{ asset('css/game.css') }}" rel="stylesheet">
@endsection

@section('scripts')
<script src="{{ asset('js/game.js') }}" defer></script>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        {{-- debug data --}}
        {{-- @php echo(print_r($data)) @endphp --}}
        @isset($data)
        <div class="col-12 text-center mb-3">
            <h2>Color {{$data["step_number"]}}</h2>
        </div>
        <div class="col-12 row">
            <div class="col-6">
                {{-- Color picker --}}
                <div>
                    <div id="picker"></div>
                    <div class="pl-4 mt-3">
                        <span class="text-black-bold">Selected Color:</span>
                        <div id="values" class="text-black-bold"></div>
                    </div>
                </div>
                {{-- Timer --}}
                {{-- Score --}}
            </div>
            <div class="col-6 align-item-center">
                {{-- Input colors --}}
                <div class="d-flex justify-content-center">
                    @foreach ($data["input_colors"] as $color)
                        <div class="d-inline-flex offset-1">
                            <div class="row">
                                <div class="col-8 justify-content-center">
                                    <div class="text-center">{{$color['name']}}</div>
                                    <div class="input-color" style="background-color: #{{$color['hex']}}"></div>
                                </div>
                                <div class="col-4 text-black-bold d-flex align-items-center">
                                    <div>50%</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{-- Mixing gif --}}
                <div class="d-flex justify-content-center mt-10">
                    <img id="mixing-gif" src="{{asset("/img/bucket.gif")}}">
                </div>
                {{-- Solution --}}
            </div>
        </div>
        @endisset
    </div>
</div>
@endsection