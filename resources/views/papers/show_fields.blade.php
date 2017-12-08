<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    {!! $paper->id !!}
</div>

<!-- Title Field -->
<div class="form-group">
    {!! Form::label('title', 'Title:') !!}
    <p>{!! $paper->title !!}</p>
</div>

<!-- Authors Field -->
<div class="form-group">
    {!! Form::label('author', 'Authors:') !!}
    <ol>
        @foreach($paper->authors as $author)
            <li>
                <a href="{!! route($routeType . 'authors.show', [$author->id]) !!}">{!! $author['given_name'] . ' ' . $author['surname'] !!}</a>
            </li>
        @endforeach
    </ol>
</div>

<!-- Cover Date Field -->
<div class="form-group">
    {!! Form::label('cover_date', 'Cover Date:') !!}
    {!! substr($paper['cover_date'], 0, 10) !!}
</div>

<!-- Url Field -->
<div class="form-group">
    {!! Form::label('url', 'URL:') !!}
    <a href="{!! $paper->url !!}" target="_blank">{!! $paper->url !!}</a>
</div>

<!-- Issn Field -->
<div class="form-group">
    {!! Form::label('issn', 'Issn:') !!}
    {!! $paper->issn !!}
</div>

<!-- Abstract Field -->
<div class="form-group">
    {!! Form::label('abstract', 'Abstract:') !!}
    <p>{!! $paper->abstract !!}</p>
</div>

<!-- Keywords Field -->
<div class="form-group">
    {!! Form::label('keyword', 'Keywords:') !!}
    {{ $keywords }}
</div>
