@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <section class="content-header row">
            <h1 class="col-sm-2 pull-left">Co-authors</h1>
            {!! Form::open(['route' => [$routeType . 'coAuthors.search'], 'method' =>
            'get']) !!}
            <div class="form-group col-sm-8">
                {!! Form::text('q', null, ['class' => 'form-control',
                'placeholder' => 'Type Author name']) !!}
            </div>
            <div class="form-group col-sm-1">
                {!! Form::submit('Search', ['class' => 'btn btn-primary btn-block']) !!}
            </div>
            {!! Form::close() !!}
        </section>
    </div>

    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
        @if(! isset($coAuthors) || count($coAuthors) == 0)
            <h1>Empty</h1>
        @else
            <div class="box-body">
                    @include('co_authors.table')
            </div>
            @if(isset($paginator))
                <div class="text-center">{{ $paginator }}</div>
            @endif
        @endif
        </div>
    </div>
@endsection

