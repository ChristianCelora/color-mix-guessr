@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        {{-- debug data --}}
        @php echo(print_r($data)) @endphp
    </div>
</div>
@endsection