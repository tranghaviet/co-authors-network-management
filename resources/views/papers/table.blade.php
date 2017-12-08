<table class="table table-responsive" id="papers-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Cover Date</th>
            {{--<th>Abstract</th>--}}
            <th>Issn</th>
            <th></th>
            @auth
            <th colspan="3">Action</th>
            @endAuth
        </tr>
    </thead>
    <tbody>
    @foreach($papers as $paper)
        <tr>
            <td>{!! $paper['id'] !!}</td>
            <td><a href="{!! route($routeType . 'papers.show', [$paper['id']]) !!}">{!! $paper['title'] !!}</a></td>
            <td>{!! substr($paper['cover_date'], 0, 10) !!}</td>
            {{--<td>{!! $paper->abstract !!}</td>--}}
            <td>{!! $paper['issn'] !!}</td>
            <td><a href="{!! $paper['url'] !!}" target="_blank">Paper on Science Direct</a></td>
            @auth
            <td>
                {!! Form::open(['route' => ['papers.destroy', $paper['id']], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('papers.edit', [$paper['id']]) !!}" class='btn btn-default btn-xs'><i class="fa fa-edit"></i></a>
                    {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
            @endAuth
        </tr>
    @endforeach
    </tbody>
</table>
