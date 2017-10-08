<table class="table table-responsive" id="papers-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Cover Date</th>
            <th>Abstract</th>
            <th>Url</th>
            <th>Issn</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($papers as $paper)
        <tr>
            <td>{!! $paper->id !!}</td>
            <td>{!! $paper->title !!}</td>
            <td>{!! $paper->cover_date !!}</td>
            <td>{!! $paper->abstract !!}</td>
            <td>{!! $paper->url !!}</td>
            <td>{!! $paper->issn !!}</td>
            <td>
                {!! Form::open(['route' => ['papers.destroy', $paper->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('papers.show', [$paper->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('papers.edit', [$paper->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
