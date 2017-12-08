@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <section class="content-header row">
            <h1 class="col-sm-1 pull-left">Authors</h1>
            {!! Form::open(['route' => [$routeType . 'authors.search'], 'method' => 'get']) !!}
            <div class="form-group col-sm-8">
                {!! Form::text('q', null, ['class' => 'form-control',
                'placeholder' => 'Type Author name or University']) !!}
            </div>
            <div class="form-group col-sm-2">
                {!! Form::submit('Search', ['class' => 'btn btn-primary btn-block']) !!}
            </div>
            {!! Form::close() !!}
            @auth
            <div class="col-sm-1 pull-right">
                <a class="btn btn-primary btn-block" style="margin-right: 35px;margin-bottom: 5px" href="{!! route('authors.create') !!}">New</a>
            </div>
            @endAuth
        </section>
    </div>

    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                @if(isset($authors) && count($authors) == 0)
                    <h1>Empty</h1>
                @else
                    @include('authors.table')
                @endif
            </div>
            @if(isset($paginator))
                <div class="text-center">{{ $paginator }}</div>
            @else
                <ul class="pagination">
                @if(isset($previousPage))
                    <li><a href="{{ $previousPage }}" rel="prev">« Previous</a></li>
                @else
                    <li class="disabled"><span>« Previous</span></li>
                @endif

                @if(isset($nextPage))
                    <li><a href="{{ $nextPage }}" rel="next">Next »</a></li>
                @else
                    <li class="disabled"><span>Next »</span></li>
                @endif
                </ul>
            @endif
        </div>
    </div>
@endsection

