@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Co Author
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($coAuthor, ['route' => ['coAuthors.update', $coAuthor->id], 'method' => 'patch']) !!}

                        @include('co_authors.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection