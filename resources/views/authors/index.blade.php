@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <section class="content-header row">
            <h1 class="col-md-6 pull-left">Authors</h1>
            <h1 class="col-md-6 pull-right">
                <a class="btn btn-primary pull-right" style="margin-right: 35px;margin-bottom: 5px" href="{!! route('authors.create') !!}">Add New</a>
            </h1>
        </section>

        <div class="row">
            {!! Form::open(['route' => ['authors.search'], 'method' => 'get']) !!}

                <div class="form-group col-sm-3">
                    {!! Form::text('author_name', null, ['class' => 'form-control', 'placeholder' => 'Author name']) !!}
                </div>
                <div class="form-group col-sm-3">
                    {!! Form::text('university', null, ['class' => 'form-control', 'placeholder' => 'University']) !!}
                </div>
                <div class="form-group col-sm-3">
                    {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Author email']) !!}
                </div>
                <div class="form-group col-sm-3">
                    {!! Form::text('paper', null, ['class' => 'form-control', 'placeholder' => 'Paper was wrote (title)']) !!}
                </div>
                <!-- Submit Field -->
                <div class="form-group col-sm-1">
                    {!! Form::submit('Search', ['class' => 'btn btn-primary']) !!}
                </div>
            {!! Form::close() !!}
        </div>
    </div>

    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('authors.table')
            </div>
            <div class="text-center">{{ $authors->render() }}</div>
        </div>
    </div>
@endsection

