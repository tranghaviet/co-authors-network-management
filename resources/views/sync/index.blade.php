@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        @include('flash::message')

        <section class="content-header row">
            <h1 class="col-sm-12 pull-left">Sync</h1>

            {!! Form::open(['route' => ['sync.coAuthors'], 'method' => 'post']) !!}
            <div class="form-group col-sm-12">
                {!! Form::submit('Co-Authors', ['class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}

            {!! Form::open(['route' => ['sync.candidates'], 'method' => 'post']) !!}
            <div class="form-group col-sm-12">
                {!! Form::submit('Candidates', ['class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}
        </section>
    </div>
@endsection
