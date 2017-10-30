<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    {!! $city->id !!}
</div>

<!-- Name Field -->
<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    {!! $city->name !!}
</div>

<!-- Country Field -->
<div class="form-group">
    {!! Form::label('country', 'Country:') !!}
    <a href="{!! route('countries.show', [$city->country['id']]) !!}">
        {!! $city->country['name'] !!}
    </a>
</div>

<!-- Universities Field -->
<div class="form-group">
    {!! Form::label('universities', 'Universities:') !!}
    <ol>
        @foreach($city->universities as $university)
            <li>
                <a href="{!! route('universities.show', [$university['id']]) !!}">{!! $university['name'] !!}</a>
            </li>
        @endforeach
    </ol>
</div>
