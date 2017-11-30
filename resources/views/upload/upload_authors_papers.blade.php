@extends('layouts.app')

@section('content')
<form action="{{route('upload_authors_papers')}}" method="post" enctype="multipart/form-data">
	{{csrf_field()}}
	<div class="form-group">
		<label for="file">Select a file to import</label>
		<input type="file" name="file" class="form-control">
	</div>
	<div class="form-group">
		<button class="btn btn-primary">
			<i class="fa fa-upload"></i>
		</button>
	</div>
</form>
@endsection