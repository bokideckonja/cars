@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            @include('partials.alert')
            <div class="panel panel-default">
                <div class="panel-heading">List of Categories</div>

                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                        <tr>
                            <td scope="row"><a href="{{ url('/admin/categories/'.$category->id.'/edit') }}">{{ $category->name }}</a> </td>                              
                            <td>
                                <a href="" onclick="event.preventDefault(); 
                                    document.getElementById('delete-job-{{$category->id}}').submit();">delete</a>
                                <form action="{{ url('/admin/categories/'.$category->id) }}" id="delete-job-{{$category->id}}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                    
            </div>
        </div>
    </div>
</div>
@endsection
