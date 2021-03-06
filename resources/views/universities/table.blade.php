<table class="table table-responsive" id="universities-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>City</th>
            @if (\App\Helpers\Utility::displayForAdmin())
            <th colspan="3">Action</th>
            @endif
        </tr>
    </thead>
    <tbody>
    @foreach($universities as $university)
        <tr>
            <td>{!! $university->id !!}</td>
            <td>
                <a href="{!! route($routeType . 'universities.show', [$university->id]) !!}">
                    {!! $university->name !!}
                </a>
            </td>
            <td>{!! $university->city['name'] !!}</td>
            @if (\App\Helpers\Utility::displayForAdmin())
            <td>
                {!! Form::open(['route' => ['universities.destroy', $university->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('universities.edit', [$university->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-edit"></i></a>
                    {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
            @endif
        </tr>
    @endforeach
    </tbody>
</table>
