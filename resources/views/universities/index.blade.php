@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <section class="content-header row">
            <h1 class="col-sm-2 pull-left">Universities</h1>
            {!! Form::open(['route' => ['universities.search'], 'method' => 'get']) !!}
            <div class="form-group col-sm-8">
                {!! Form::text('q', null, ['class' => 'form-control', 'placeholder' => 'University name']) !!}
            </div>
            <div class="form-group col-sm-1">
                {!! Form::submit('Search', ['class' => 'btn btn-primary btn-block']) !!}
            </div>
            {!! Form::close() !!}
            <div class="col-sm-1 pull-right">
                <a class="btn btn-primary btn-block" style="margin-right: 35px;margin-bottom: 5px" href="{!! route('authors.create') !!}">New</a>
            </div>
        </section>
    </div>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('universities.table')
            </div>
            <div class="text-center">{{ $universities->render() }}</div>
        </div>
    </div>
@endsection
