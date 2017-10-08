<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $coAuthor->id !!}</p>
</div>

<!-- First Author Field -->
<div class="form-group">
    {!! Form::label('first_author_id', 'First Author:') !!}
    <p>
        <a href="{!! route('authors.show', [$coAuthor->firstAuthor->id]) !!}">
            {!! $coAuthor->firstAuthor->given_name.' '.$coAuthor->firstAuthor->surname !!}
        </a>
    </p>
</div>

<!-- Second Author Field -->
<div class="form-group">
    {!! Form::label('second_author_id', 'Second Author Id:') !!}
    <p>
        <a href="{!! route('authors.show', [$coAuthor->secondAuthor->id]) !!}">
            {!! $coAuthor->secondAuthor->given_name.' '.$coAuthor->secondAuthor->surname !!}
        </a>
    </p>
</div>

