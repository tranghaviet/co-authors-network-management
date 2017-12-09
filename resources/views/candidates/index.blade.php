@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <section class="content-header row">
            <h1 class="col-sm-2 pull-left">Candidates</h1>
            {!! Form::open(['route' => [$routeType . 'coAuthors.search'], 'method' => 'get']) !!}
                <div class="form-group col-sm-2">
                    {!! Form::text('q', null, ['class' => 'form-control',
                    'placeholder' => 'Type Author name']) !!}
                </div>
                <div class="form-group col-sm-2">
                    {!! Form::number('score_1', null, ['class' => 'form-control', 'min' => 0,
                    'placeholder' => 'Score 1']) !!}
                </div>
                <div class="form-group col-sm-2">
                    {!! Form::number('score_2', null, ['class' => 'form-control', 'min' => 0,
                    'placeholder' => 'Score 2']) !!}
                </div>
                <div class="form-group col-sm-2">
                    {!! Form::number('score_3', null, ['class' => 'form-control', 'min' => 0,
                    'placeholder' => 'Score 3']) !!}
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
                @if(! isset($candidates) || count($candidates) == 0)
                    <h1>Empty</h1>
                @else
                    @include('candidates.table')
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



