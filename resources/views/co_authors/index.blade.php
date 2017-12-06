@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <section class="content-header row">
            <h1 class="col-sm-2 pull-left">Co-authors</h1>
            {!! Form::open(['route' => ['coAuthors.search'], 'method' =>
            'get']) !!}
            <div class="form-group col-sm-8">
                {!! Form::text('q', null, ['class' => 'form-control',
                'placeholder' => 'Type Author name or University']) !!}
            </div>
            <div class="form-group col-sm-1">
                {!! Form::submit('Search', ['class' => 'btn btn-primary btn-block']) !!}
            </div>
            {!! Form::close() !!}
            @auth
            <div class="col-sm-1 pull-right">
                <a class="btn btn-primary btn-block" style="margin-right:
                35px;margin-bottom: 5px" href="{!! route('coAuthors.create')
                !!}">New</a>
            </div>
            @endAuth
        </section>
    </div>

    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
        @if(isset($candidates))
            <div class="box-body">
                    @include('co_authors.table')
            </div>
            <div class="text-center">{{ $coAuthors->render() }}</div>
        @else
            <h1>Empty</h1>
        @endif
        </div>
    </div>
@endsection

