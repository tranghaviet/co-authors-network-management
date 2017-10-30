<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $country->id !!}</p>
</div>

<!-- Name Field -->
<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    <p>{!! $country->name !!}</p>
</div>

<!-- Cities Field -->
<div class="form-group">
    {!! Form::label('cities', 'Cities:') !!}
    <ol>
        @foreach($country->cities as $city)
            <li>
                <a href="{!! route('cities.show', [$city['id']]) !!}">{!! $city['name'] !!}</a>
            </li>
        @endforeach
    </ol>
</div>
