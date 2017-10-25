<!-- Author Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('author_id', 'Author Id:') !!}
    {!! Form::number('author_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Paper Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('paper_id', 'Paper Id:') !!}
    {!! Form::number('paper_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('authorPapers.index') !!}" class="btn btn-default">Cancel</a>
</div>
