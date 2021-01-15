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
                            <div style="background-color: #{{$step["user_guess"]["hex"]}}">
                                #{{$step["user_guess"]}}
                            </div>
                        </td>
                        <td>
                            <div style="background-color: #{{$step["solution"]["hex"]}}">
                                #{{$step["solution"]["hex"]}}
                            </div>
                        </td>
                        <td>{{$step["score"]."/".$data["max_score"]}}</td>
                    </tr>
                @endforeach
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