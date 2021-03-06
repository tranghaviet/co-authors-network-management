@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <section class="content-header row">
            <h1 class="col-sm-2 pull-left">Co-authors</h1>
            {!! Form::open(['route' => [$routeType . 'coAuthors.search'], 'method' =>
            'get']) !!}
            <div class="form-group col-sm-3">
                {!! Form::text('q', null, ['class' => 'form-control',
                'placeholder' => 'Type Author name or university']) !!}
            </div>
            <div class="form-group col-sm-3">
                {!! Form::number('no_of_mutual_authors', null, ['class' => 'form-control', 'min' => 1,
                'placeholder' => 'No. of Mutual authors']) !!}
            </div>
            <div class="form-group col-sm-3">
                {!! Form::number('no_of_joint_papers', null, ['class' => 'form-control', 'min' => 1,
                'placeholder' => 'No. of Joint Papers']) !!}
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
        @if(! isset($coAuthors) || count($coAuthors) == 0)
            <h1>Empty</h1>
        @else
            <div class="box-body">
                    @include('co_authors.table')
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
        @endif
        </div>
    </div>
@endsection

