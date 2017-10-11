<table class="table table-responsive" id="authors-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Given Name</th>
            <th>Surname</th>
            <th>Email</th>
            <th>University</th>
            <th>Url</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($authors as $author)
        <tr>
            <td>{!! $author->id !!}</td>
            <td>{!! $author->given_name !!}</td>
            <td>{!! $author->surname !!}</td>
            <td>{!! $author->email !!}</td>
            <td>{!! $author->university['name'] !!}</td>
            <td>{!! $author->url !!}</td>
            <td>
                {!! Form::open(['route' => ['authors.destroy', $author->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('authors.show', [$author->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('authors.edit', [$author->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
