<!-- Title Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('title', 'Title:') !!}
    {!! Form::textarea('title', null, ['class' => 'form-control']) !!}
</div>

<!-- Cover Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('cover_date', 'Cover Date:') !!}
    {!! Form::date('cover_date', null, ['class' => 'form-control']) !!}
</div>

<!-- Abstract Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('abstract', 'Abstract:') !!}
    {!! Form::textarea('abstract', null, ['class' => 'form-control']) !!}
</div>

<!-- Url Field -->
<div class="form-group col-sm-6">
    {!! Form::label('url', 'Url:') !!}
    {!! Form::text('url', null, ['class' => 'form-control']) !!}
</div>

<!-- Issn Field -->
<div class="form-group col-sm-6">
    {!! Form::label('issn', 'Issn:') !!}
    {!! Form::text('issn', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('papers.index') !!}" class="btn btn-default">Cancel</a>
</div>
