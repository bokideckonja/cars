@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            @include('partials.alert')
            <div class="panel panel-default">
                <div class="panel-heading">Vehicles</div>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Info</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($vehicles as $vehicle)
                    <tr class="{{ $vehicle->status == 'pending'?'info':''}}">
                        <td style="width: 200px"><img src="{{ asset($vehicle->image) }}" alt="" class="img-responsive"></td>
                        <td>
                            <h4><strong>{{$vehicle->name}}</strong></h4>
                            <p>
                                Year: <strong>{{$vehicle->year}}</strong><br/>
                                Mileage: <strong>{{$vehicle->miles}} km</strong><br/>
                                Brand: <strong>{{ $vehicle->category->name }}</strong>
                            </p>
                        </td>
                        <td><strong>{{$vehicle->price}} €</strong></td>
                    </tr>
                    @endforeach
                </table>
            </div>
            {{ $vehicles->links() }}
        </div>
    </div>
</div>
@endsection
