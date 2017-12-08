@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <section class="content-header row">
            <h1 class="col-sm-12 pull-left">Sync</h1>

            <button onclick="alert('Syncing co-authors.')">Co-authors</button>
            <button onclick="alert('Syncing candidate.')">Candidates</button>
            {{--{!! Form::open(['route' => ['sync'], 'method' => 'post']) !!}--}}
            {{--<div class="form-group col-sm-12">--}}
                {{--{!! Form::text('q', null, ['class' => 'form-control',--}}
                {{--'placeholder' => 'Type Author name or University']) !!}--}}
            {{--</div>--}}
            {{--<div class="form-group col-sm-2">--}}
                {{--{!! Form::submit('Search', ['class' => 'btn btn-primary btn-block']) !!}--}}
            {{--</div>--}}
            {{--{!! Form::close() !!}--}}
            {{--@auth--}}
                {{--<div class="col-sm-1 pull-right">--}}
                    {{--<a class="btn btn-primary btn-block" style="margin-right: 35px;margin-bottom: 5px" href="{!! route('authors.create') !!}">New</a>--}}
                {{--</div>--}}
            {{--@endAuth--}}
        </section>
    </div>
@endsection

