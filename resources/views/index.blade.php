@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row d-flex justify-content-center mt-4">
            <h1>{{ config('app.name', 'Laravel') }}</h1>
        </div>
        <div class="row d-flex justify-content-center mt-5">
            <div class="redirect btn btn-outline-primary" data-url="new-game">
                <strong>New Game</strong>
            </div>
        </div>
    </div>
@endsection