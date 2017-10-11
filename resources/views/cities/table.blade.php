<table class="table table-responsive" id="cities-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Country</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($cities as $city)
        <tr>
            <td>{!! $city->id !!}</td>
            <td>{!! $city->name !!}</td>
            <td>{!! $city->country['name'] !!}</td>
            <td>
                {!! Form::open(['route' => ['cities.destroy', $city->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('cities.show', [$city->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-eye"></i></a>
                    <a href="{!! route('cities.edit', [$city->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-edit"></i></a>
                    {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
