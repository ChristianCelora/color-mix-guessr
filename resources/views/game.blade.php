@extends('layouts.app')

@section('styles')
<link href="{{ asset('css/game.css') }}" rel="stylesheet">
@endsection

@section('scripts')
<script src="{{ asset('js/game.js') }}" defer></script>
@isset($data["seconds"])
<script>
    window.seconds_left = "{{$data["seconds"]}}";
    window.game_id = "{{$data["game_id"]}}";
    window.route_api_solution = "{{route("user-guess")}}";
</script>
@endisset
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        {{-- debug data --}}
        @php echo(print_r($data)) @endphp
        @isset($data)
        <div class="col-12 text-center mb-3">
            <h2>Color {{$data["step_number"]}}</h2>
        </div>
        <div class="col-12 row">
            <div class="col-6">
                {{-- Color picker --}}
                <div id="color-picker">
                    <div id="picker"></div>
                    <div class="pl-5 mt-5">
                        <span class="text-black-bold">Selected Color:</span>
                        <div id="values" class="text-black-bold"></div>
                        <div class="input-color mt-2"></div>
                    </div>
                </div>
                {{-- Timer --}}
                <div class="mt-5 ml-5">
                    <div id="game-timer">
                        <div id="time-left" class="text-black-bold">00:{{($data["seconds"] < 10) ? "0".$data["seconds"] : $data["seconds"]}}</div>
                    </div>
                </div>
                {{-- Score --}}
                <div class="mt-5 ml-5">
                    <div id="score">
                        <span class="text-black-bold">Score:</span>
                        <div class="progress">
                            <div id="score-progress-bar" class="progress-bar" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div id="score-label"></div>
                    </div>
                </div>
            </div>
            <div class="col-6 align-item-center">
                {{-- Input colors --}}
                <div class="mx-auto">
                <div class="d-table-row justify-content-center mx-auto">
                    @foreach ($data["input_colors"] as $color)
                        @php 
                            $color_sum = $color["red"] + $color["green"] + $color["blue"];
                            $text_color = ($color_sum > 382) ? "black" : "white"; 
                        @endphp
                        <div class="d-table-cell mt-2">
                            <div class="row">
                                <div class="col-12 justify-content-center">
                                    <div class="d-flex align-items-center mx-auto input-color" style="background-color: #{{$color['hex']}}">
                                        <div class="font-weight-bold w-100 text-center">
                                            <div style="color: {{$text_color}}">{{$color["weight"]}}%</div>
                                        </div>
                                    </div>
                                    <div class="text-center mt-2">{{$color["name"]}}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
                {{-- Mixing gif --}}
                <div class="d-flex justify-content-center mt-10 col-12">
                    <div>
                        <img id="mixing-gif" src="{{asset("/img/bucket.gif")}}">
                    </div>
                </div>
                {{-- Solution --}}
                @php $color = $data['solution'] @endphp
                <div class="row mt-10">
                    <div id="solution-placeholder" class="col-12 d-flex justify-content-center">
                        <div class="row">
                            <div class="col-12 justify-content-center">
                                <div class="text-center">
                                    Solution
                                </div>
                                <div class="input-color mt-3">
                                    <img class="placeholder" src="{{asset("/img/q_mark_placeholder.png")}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="solution" class="col-12 d-flex justify-content-center" style="display:none !important">
                        <div class="row">
                            <div class="col-12 justify-content-center">
                                <div class="text-center">Solution</div>
                                <div class="input-color mt-3"></div>
                                <div class="color-label text-center"></div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Next step --}}
                <div id="next-step" class="row mt-10" style="display: none">
                    <div class="w-100 d-flex justify-content-center">
                        <form action="{{route("next-step")}}" method="POST">
                            @csrf
                            <input type="hidden" name="game_id" value="{{$data["game_id"]}}">
                            <button type="submit" class="btn btn-outline-primary">Next</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endisset
    </div>
</div>
@endsection