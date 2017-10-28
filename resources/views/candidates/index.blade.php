@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <section class="content-header row">
            <h1 class="col-sm-6 pull-left">Candidates</h1>
            <div class="col-sm-6 pull-right">
                <a class="btn btn-primary pull-right" style="margin-right: 35px;margin-bottom: 5px" href="{!! route('candidates.create') !!}">New</a>
            </div>
        </section>
        <div class="row">
            {!! Form::open(['route' => ['candidates.search'], 'method' => 'get']) !!}
            <div class="form-group col-sm-3">
                {!! Form::text('q', null, ['class' => 'form-control',
                'placeholder' => 'Type Author name']) !!}
            </div>
            <div class="form-group col-sm-3">
                {!! Form::number('no_of_mutual_authors', null, ['class' =>
                'form-control',
                'placeholder' => 'No. of Mutual authors']) !!}
            </div>
            <div class="form-group col-sm-3">
                {!! Form::number('no_of_joint_papers', null, ['class' => 'form-control',
                'placeholder' => 'No. of Joint Papers']) !!}
            </div>
            <div class="form-group col-sm-3">
                {!! Form::number('no_of_joint_subjects', null, ['class' => 'form-control',
                'placeholder' => 'No. of Joint Subjects']) !!}
            </div>
            <div class="form-group col-sm-3">
                {!! Form::number('no_of_joint_keywords', null, ['class' => 'form-control',
                'placeholder' => 'No. of Joint Keywords']) !!}
            </div>
            <div class="form-group col-sm-3">
                {!! Form::number('score_1', null, ['class' => 'form-control',
                'placeholder' => 'Score 1']) !!}
            </div>
            <div class="form-group col-sm-3">
                {!! Form::number('score_2', null, ['class' => 'form-control',
                'placeholder' => 'Score 2']) !!}
            </div>
            <div class="form-group col-sm-3">
                {!! Form::number('score_3', null, ['class' => 'form-control',
                'placeholder' => 'Score 3']) !!}
            </div>
            <div class="form-group col-sm-3">
                {!! Form::submit('Search', ['class' => 'btn btn-primary btn-block']) !!}
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
                    @if($candidates->isEmpty())
                        <h1>Empty</h1>
                    @else
                        @include('candidates.table')
                    @endif
            </div>
            @if($isPaginated)
                <div class="text-center">{{ $candidates->render() }}</div>
            @endif
        </div>
    </div>
@endsection



