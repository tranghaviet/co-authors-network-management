@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <section class="content-header row">
            <h1 class="col-sm-2 pull-left">Candidates</h1>
            {!! Form::open(['route' => [$routeType . 'candidates.search'], 'method' => 'get']) !!}
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
                    <table class="table table-responsive" id="candidates-table">
                        <thead>
                        <tr>
                            <th>First Author</th>
                            <th>Second Author</th>
                            <th>Score 1</th>
                            <th>Score 2</th>
                            <th>Score 3</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($candidates as $candidate)
                            <tr>
                                <td>

                                    <a href="{!! route($routeType . 'authors.show', [$candidate['first_author']['id']]) !!}">
                                        {!! $candidate['first_author']['given_name'].' '.$candidate['first_author']['surname'] !!}
                                    </a>
                                </td>
                                <td>
                                    <a href="{!! route($routeType . 'authors.show', [$candidate['second_author']['id']]) !!}">
                                        {!! $candidate['second_author']['given_name'].' '.$candidate['second_author']['surname'] !!}
                                    </a>
                                </td>
                                <td>{!! $candidate['score_1'] !!}</td>
                                <td>{!! $candidate['score_2'] !!}</td>
                                <td>{!! $candidate['score_3'] !!}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
@endsection



