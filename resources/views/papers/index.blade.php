@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <section class="content-header row">
            <h1 class="col-sm-1 pull-left">Papers</h1>
            {!! Form::open(['route' => [$routeType . 'papers.search'], 'method' => 'get']) !!}
            <div class="form-group col-sm-8">
                {!! Form::text('q', null, ['class' => 'form-control',
                'placeholder' => 'Type ID, Title, ISSN or Keywords']) !!}
            </div>
            <div class="form-group col-sm-2">
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
            <div class="box-body">
                    @include('papers.table')
            </div>
            @if(isset($paginator))
                <div class="text-center">{{ $paginator }}</div>
            @endif
        </div>
    </div>
@endsection

