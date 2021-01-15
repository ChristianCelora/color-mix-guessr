@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-10">
        @isset($data)
        <table class="table col-10">
            <thead>
                <tr class="table-primary text-center">
                    <th scope="col">Number</th>
                    <th scope="col">User Guess</th>
                    <th scope="col">Solution</th>
                    <th scope="col">Score</th>
                </tr>
            </thead>
            <tbody>
                @isset($data["steps"])
                @foreach ($data["steps"] as $step)
                    <tr class="text-center">
                        <th scope="row">{{$step["number"]}}</th>
                        <td>
                            @php $text_color_1 = (array_sum($step["user_guess"]["rgb"]) > 382) ? "black" : "white"; @endphp
                            <div style="color: {{$text_color_1}}; background-color: #{{$step["user_guess"]["hex"]}}">
                                #{{$step["user_guess"]["hex"]}}
                            </div>
                        </td>
                        <td>
                            @php 
                                $color_sum = $step["solution"]["red"] + $step["solution"]["green"] + $step["solution"]["blue"];
                                $text_color_2 = ($color_sum > 382) ? "black" : "white"; 
                            @endphp
                            <div style="color: {{$text_color_1}}; background-color: #{{$step["solution"]["hex"]}}">
                                #{{$step["solution"]["hex"]}}
                            </div>
                        </td>
                        <td>{{$step["score"]."/".$data["max_score"]}}</td>
                    </tr>
                @endforeach
                @endisset
                @isset($data["totals"])
                <tr class="table-success text-center font-weight-bold">
                    <td colspan=3>Total score</td>
                    <td>{{$data["totals"]["score"]."/".$data["totals"]["max_score"]}}</td>
                </tr>
                @endisset
            </tbody>
        </table>
        @endisset
    </div>
    <div class="row d-flex justify-content-center mt-5">
        <div class="redirect btn btn-outline-success" data-url="{{route("new-game")}}">
            <strong>New game</strong>
        </div>
        <div class="col-1"></div>
        <div class="redirect btn btn-outline-primary" data-url="{{route("index")}}">
            <strong>Return</strong>
        </div>
    </div>
</div>
@endsection