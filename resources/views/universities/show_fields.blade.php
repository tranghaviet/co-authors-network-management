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
        <a href="{!! route('cities.show', [$university->city['id']]) !!}">
            {!! $university->city['name'] !!}
        </a>
</div>

<!-- Authors Field -->
<div class="form-group">
    {!! Form::label('authors', 'Authors:') !!}
    <ol>
        @foreach($university->authors as $author)
            <li>
                <a href="{!! route('authors.show', [$author->id]) !!}">{!! $author['given_name'] . ' ' . $author['surname'] !!}</a>
            </li>
        @endforeach
    </ol>
</div>
