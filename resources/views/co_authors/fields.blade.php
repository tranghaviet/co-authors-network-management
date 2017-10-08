<!-- First Author Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('first_author_id', 'First Author Id:') !!}
    {!! Form::number('first_author_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Second Author Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('second_author_id', 'Second Author Id:') !!}
    {!! Form::number('second_author_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('coAuthors.index') !!}" class="btn btn-default">Cancel</a>
</div>
