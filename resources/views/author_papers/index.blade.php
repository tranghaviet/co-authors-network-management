@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <section class="content-header row">
            <h1 class="col-sm-2 pull-left">Author Paper</h1>
            {!! Form::open(['route' => [$routeType . 'authorPaper.search'], 'method' => 'get']) !!}
            <div class="form-group col-sm-7">
                {!! Form::text('q', null, ['class' => 'form-control',
                'placeholder' => 'Type Author name or Paper title']) !!}
            </div>
            <div class="form-group col-sm-2">
                {!! Form::select('type',['author' => 'author', 'paper' => 'paper'], 'author', ['class' => 'form-control']) !!}
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
            <div class="box-body">
                    @include('author_papers.table')
            </div>
            @if(isset($authorPapers))
            <div class="text-center">{{ $authorPapers->render() }}</div>
            @endif
        </div>
    </div>
@endsection

