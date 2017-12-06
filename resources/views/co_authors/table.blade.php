<table class="table table-responsive" id="coAuthors-table">
    <thead>
        <tr>
            <th>Id</th>
            <th>First Author</th>
            <th>University</th>
            <th>Second Author</th>
            <th>University</th>
            <th>Joint papers</th>
            @auth
            <th colspan="3">Action</th>
            @endAuth
        </tr>
    </thead>
    <tbody>
    @foreach($coAuthors as $coAuthor)
        <tr>
            <td>{!! $coAuthor->id !!}</td>
            <td>
                <a href="{!! route('authors.show', [$coAuthor['first_author_id']]) !!}">
                    {!! $coAuthor['firstAuthor']['given_name'].' '.$coAuthor['firstAuthor']['surname'] !!}
                </a>
            </td>
            <td>
                {!! $coAuthor['firstAuthor']->university['name'] !!}
            </td>
            <td>
                <a href="{!! route('authors.show', [$coAuthor['second_author_id']]) !!}">
                    {!! $coAuthor['secondAuthor']['given_name'].' '.$coAuthor['secondAuthor']['surname'] !!}
                </a>
            </td>
            <td>
                {!! $coAuthor['secondAuthor']['university']['name'] !!}
            </td>
            <td>
                {{ $coAuthor['candidate']['no_of_joint_papers'] }}
            </td>
            <td>
                {!! Form::open(['route' => ['coAuthors.destroy', $coAuthor->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('coAuthors.show', [$coAuthor->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-eye"></i></a>
                    <a href="{!! route('coAuthors.edit', [$coAuthor->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-edit"></i></a>
                    {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
