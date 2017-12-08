<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    {!! $university->id !!}
</div>

<!-- Name Field -->
<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    {!! $university->name !!}
</div>

<!-- City Field -->
<div class="form-group">
    {!! Form::label('city', 'City:') !!}
        {!! $university->city['name'] !!}
</div>
<!-- Country Field -->
<div class="form-group">
    {!! Form::label('country', 'Country:') !!}
        {!! $university['city']['country']['name'] !!}
</div>

<!-- Authors Field -->
<div class="form-group">
    {!! Form::label('authors', 'Authors:') !!} {{ $university->authors->count() }}
    <ol>
        @foreach($university->authors as $author)
            <li>
                <a href="{!! route($routeType . 'authors.show', [$author->id]) !!}">{!! $author['given_name'] . ' ' . $author['surname'] !!}</a>
            </li>
        @endforeach
    </ol>
</div>
