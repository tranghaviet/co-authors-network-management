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
                    <table class="table table-responsive" id="candidates-table">
                    <thead>
                        <tr>
                            <th>First Author</th>
                            <th>Second Author</th>
                            <th>Score 1</th>
                            <th>Score 2</th>
                            <th>Score 3</th>
                            @auth
                            <th colspan="3">Action</th>
                            @endAuth
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($candidates as $candidate)
                        <tr>
                            <td>
                            
                                <a href="{!! route('authors.show', [$candidate['firstAuthor']['id']]) !!}">
                                    {!! $candidate['firstAuthor']['given_name'].' '.$candidate['firstAuthor']['surname'] !!}
                                </a>
                            </td>
                            <td>
                                <a href="{!! route('authors.show', [$candidate['secondAuthor']['id']]) !!}">
                                    {!! $candidate['secondAuthor']['given_name'].' '.$candidate['secondAuthor']['surname'] !!}
                                </a>
                            </td>
                            <td>{!! $candidate['score_1'] !!}</td>
                            <td>{!! $candidate['score_2'] !!}</td>
                            <td>{!! $candidate['score_3'] !!}</td>
                            @auth
                            <td>
                                {!! Form::open(['route' => ['candidates.destroy', $candidate['id']], 'method' => 'delete']) !!}
                                <div class='btn-group'>
                                    <a href="{!! route('candidates.show', [$candidate['id']]) !!}" class='btn btn-default btn-xs'><i class="fa fa-eye"></i></a>
                                    <a href="{!! route('candidates.edit', [$candidate['id']]) !!}" class='btn btn-default btn-xs'><i class="fa fa-edit"></i></a>
                                    {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                                </div>
                                {!! Form::close() !!}
                            </td>
                            @endAuth
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
@endsection



