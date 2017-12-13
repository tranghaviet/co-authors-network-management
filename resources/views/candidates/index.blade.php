@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <section class="content-header row">
            <h1 class="col-sm-2 pull-left">Candidates</h1>
            {!! Form::open(['route' => [$routeType . 'candidates.search'], 'method' => 'get']) !!}
            <div class="form-group col-sm-3">
                {!! Form::text('q', null, ['class' => 'form-control', 'required' => true,
                'placeholder' => 'Type Author name or University']) !!}
            </div>
            <div class="col-sm-5">
                <div class="row">
                    <div class="form-group col-sm-4">
                        {!! Form::number('score_1', null, ['class' => 'form-control', 'min' => 0, 'step'=>'any',
                        'placeholder' => 'Score 1']) !!}
                    </div>
                    <div class="form-group col-sm-4">
                        {!! Form::number('score_2', null, ['class' => 'form-control', 'min' => 0, 'step'=>'any',
                        'placeholder' => 'Score 2']) !!}
                    </div>
                    <div class="form-group col-sm-4">
                        {!! Form::number('score_3', null, ['class' => 'form-control', 'min' => 0, 'step'=>'any',
                        'placeholder' => 'Score 3']) !!}
                    </div>
                </div>
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
            @if(! isset($candidates) || count($candidates) == 0)
                <h1>Empty</h1>
            @else
                <div class="box-body">
                    @include('candidates.table')
                </div>
                @if(isset($paginator))
                    <div class="text-center">{{ $paginator }}</div>
                @endif
            @endif
        </div>
    </div>
@endsection



