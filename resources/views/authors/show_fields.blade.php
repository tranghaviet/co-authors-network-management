<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    {!! $author->id !!}
</div>

<!-- Given Name Field -->
<div class="form-group">
    {!! Form::label('given_name', 'Given Name:') !!}
    {!! $author->given_name !!}
</div>

<!-- Surname Field -->
<div class="form-group">
    {!! Form::label('surname', 'Surname:') !!}
    {!! $author->surname !!}
</div>

<!-- Email Field -->
<div class="form-group">
    {!! Form::label('email', 'Email:') !!}
    {!! $author->email !!}
</div>

<!-- University Field -->
<div class="form-group">
    {!! Form::label('university', 'University:') !!}
    {!! $author->university['name'] or 'NULL' !!}
</div>

<!-- City Field -->
<div class="form-group">
    {!! Form::label('city', 'City:') !!}
    {!! $author->university->city['name'] or 'NULL' !!}
</div>

<!-- Country Field -->
<div class="form-group">
    {!! Form::label('country', 'Country:') !!}
    {!! $author->university->city->country['name'] or 'NULL' !!}
</div>

<!-- Subjects Field -->
<div class="form-group">
    {!! Form::label('subject', 'Subjects:') !!}
    {{ $subjects }}
</div>

<!-- Url Field -->
<div class="form-group">
    {!! Form::label('url', 'URL:') !!}
    <a href="{!! $author->url !!}" target="_blank">{!! $author->url !!}</a>
</div>

<!-- TODO: list papers, co-authors, candidates -->

<!-- Papers Field -->
<div class="form-group">
    {!! Form::label('papers', 'Papers:') !!} {{ $papers->count() }}
    <ol>
    @foreach($papers as $paper)
        <li>
            <a href="{!! route($routeType . 'papers.show', [$paper->id]) !!}">
                {!! $paper['title'] !!}
            </a>
        </li>
    @endforeach
    </ol>
</div>

<!-- Collaborators Field -->
<div class="form-group">
    {!! Form::label('collaborator', 'Collaborators:') !!} {{ $collaborators->count() }}
    <ol>
        @foreach($collaborators as $collaborator)
            <li>
                <a href="{!! route($routeType . 'authors.show', [$collaborator['id']]) !!}">{!! $collaborator['given_name'] . ' ' . $collaborator['surname'] !!}</a>
            </li>
        @endforeach
    </ol>
</div>
