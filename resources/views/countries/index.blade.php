@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Countries</h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('countries.table')
            </div>
            <div class="text-center">{{ $countries->render() }}</div>
        </div>
    </div>
@endsection

