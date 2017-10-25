<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    {!! $coAuthor->id !!}
</div>

<!-- First Author Field -->
<div class="form-group">
    {!! Form::label('first_author_id', 'First Author:') !!}

        <a href="{!! route('authors.show', [$coAuthor->firstAuthor->id]) !!}">
            {!! $coAuthor->firstAuthor->given_name.' '.$coAuthor->firstAuthor->surname !!}
        </a>

</div>

<!-- Second Author Field -->
<div class="form-group">
    {!! Form::label('second_author_id', 'Second Author:') !!}

        <a href="{!! route('authors.show', [$coAuthor->secondAuthor->id]) !!}">
            {!! $coAuthor->secondAuthor->given_name.' '.$coAuthor->secondAuthor->surname !!}
        </a>

</div>

