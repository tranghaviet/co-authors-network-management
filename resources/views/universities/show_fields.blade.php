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

<!-- City Id Field -->
<div class="form-group">
    {!! Form::label('city_id', 'City Id:') !!}
    {!! $university->city_id !!}
</div>

<!-- Authors Field -->
<div class="form-group">
    {!! Form::label('author', 'Authors:') !!}
    <ol>
        @foreach($university->authors as $author)
            <li>
                <a href="{!! route('authors.show', [$author->id]) !!}">{!! $author['given_name'] . ' ' . $author['surname'] !!}</a>
            </li>
        @endforeach
    </ol>
</div>
