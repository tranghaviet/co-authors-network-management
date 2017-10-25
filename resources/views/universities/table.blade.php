<table class="table table-responsive" id="universities-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>City</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($universities as $university)
        <tr>
            <td>{!! $university->id !!}</td>
            <td>{!! $university->name !!}</td>
            <td>{!! $university->city['name'] !!}</td>
            <td>
                {!! Form::open(['route' => ['universities.destroy', $university->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('universities.show', [$university->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-eye"></i></a>
                    <a href="{!! route('universities.edit', [$university->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-edit"></i></a>
                    {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
