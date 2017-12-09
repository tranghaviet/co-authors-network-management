@extends('layouts.app')

@section('content')
<div class="clearfix"></div>

@include('flash::message')

<div class="clearfix"></div>

<form action="{{route('upload_authors')}}" method="post" enctype="multipart/form-data">
	{{csrf_field()}}
	<div class="form-group">
		<h1 for="file">Select a file to import</h1>
		<input type="file" name="file" class="form-control">
	</div>
	<div class="form-group">
		<button class="btn btn-primary">
			<i class="fa fa-upload"> Upload</i>
		</button>
	</div>
</form>
@endsection