@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Oglasi</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    @foreach($vehicles as $vehicle)
                    <artice class="row">
                        <div class="col-xs-2">
                            <img src="http://lorempixel.com/640/480/cats/" alt="" class="img-responsive">
                        </div>
                        <div class="col-xs-8">
                            <h4>{{$vehicle->name}}</h4>
                            <p>
                                {{$vehicle->year}} .god, {{$vehicle->miles}} km
                            </p>
                        </div>
                        <div class="col-xs-2">
                            <p><strong>{{$vehicle->price}} €</strong></p>
                        </div>
                    </artice>
                    @endforeach

                </div>
            </div>
            {{ $vehicles->links() }}
        </div>
    </div>
</div>
@endsection
