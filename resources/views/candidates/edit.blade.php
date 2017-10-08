@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Candidate
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($candidate, ['route' => ['candidates.update', $candidate->id], 'method' => 'patch']) !!}

                        @include('candidates.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection