@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row d-flex justify-content-center mt-4">
            <h1>{{ config('app.name', 'Laravel') }}</h1>
        </div>
        <form action="{{route("new-game")}}" method="POST">
            @csrf
            <div class="row d-flex justify-content-center mt-5">
                <button class="btn btn-outline-success p-3" type="submit" >
                {{-- <div class="redirect btn btn-outline-success p-3" type="submit" data-url="new-game"> --}}
                    <strong>New Game</strong>
                </button>
            </div>
            <div class="row d-flex justify-content-center text-black-bold mt-5">
                <label>Select Difficulty:</label>
            </div>
            <div class="row d-flex justify-content-center mt-2">
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-outline-primary text-black-bold px-4 active">
                        <input type="radio" name="difficulty" id="option1" autocomplete="off" value="1" checked > Easy
                    </label>
                    <label class="btn btn-outline-warning text-black-bold px-4">
                        <input type="radio" name="difficulty" id="option2" autocomplete="off" value="2"> Medium
                    </label>
                    <label class="btn btn-outline-danger text-black-bold px-4">
                        <input type="radio" name="difficulty" id="option3" autocomplete="off" value="3"> Hard
                    </label>
                </div>
            </div>
        </form>
    </div>
@endsection