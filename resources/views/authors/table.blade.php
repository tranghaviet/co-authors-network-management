<table class="table table-responsive" id="authors-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Given Name</th>
            <th>Surname</th>
            <th>Email</th>
            <th>University</th>
            <th>URL</th>
            @if (\App\Helpers\Utility::displayForAdmin())
                <th colspan="3">Action</th>
            @endif
        </tr>
    </thead>
    <tbody>
    @foreach($authors as $author)
        <tr>
            <td><a href="{!! route($routeType . 'authors.show', [$author['id']]) !!}">{!! $author['id'] !!}</a></td>
            <td>{!! $author['given_name'] !!}</td>
            <td>{!! $author['surname'] !!}</td>
            <td>{!! $author['email'] !!}</td>
            <td><a href="{!! route($routeType . 'universities.show', [$author['university_id']]) !!}">{!! $author['university']['name'] !!}</a></td>
            <td><a href="{!! $author['url'] !!}" target="_blank">Author on Scopus</a></td>
            @if (\App\Helpers\Utility::displayForAdmin())
            <td>
                {!! Form::open(['route' => ['authors.destroy', $author['id']], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('authors.edit', [$author['id']]) !!}" class='btn btn-default btn-xs'><i class="fa fa-edit"></i></a>
                    {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
            @endif
        </tr>
    @endforeach
    </tbody>
</table>
