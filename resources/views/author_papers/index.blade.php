@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <section class="content-header row">
            <h1 class="col-sm-2 pull-left">Author Paper</h1>
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

