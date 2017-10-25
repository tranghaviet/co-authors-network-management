@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Paper
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($paper, ['route' => ['papers.update', $paper->id], 'method' => 'patch']) !!}

                        @include('papers.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection