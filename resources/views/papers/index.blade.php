@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <section class="content-header row">
            <h1 class="col-sm-1 pull-left">Papers</h1>
            {!! Form::open(['route' => [$routeType . 'papers.search'], 'method' => 'get']) !!}
            <div class="form-group col-sm-8">
                {!! Form::text('q', null, ['class' => 'form-control', 'required' => true,
                'placeholder' => 'Type ID, Title, ISSN', 'required' => true]) !!}
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
                    @include('papers.table')
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

