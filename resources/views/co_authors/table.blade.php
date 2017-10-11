<table class="table table-responsive" id="coAuthors-table">
    <thead>
        <tr>
            <th>Id</th>
            <th>First Author</th>
            <th>Second Author</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($coAuthors as $coAuthor)
        <tr>
            <td>{!! $coAuthor->id !!}</td>
            <td>
                <a href="{!! route('authors.show', [$coAuthor->firstAuthor->id]) !!}">
                    {!! $coAuthor->firstAuthor->given_name.' '.$coAuthor->firstAuthor->surname !!}
                </a>
            </td>
            <td>
                <!-- {!! $coAuthor->firstAuthor !!} -->
            </td>
            <td>
                <a href="{!! route('authors.show', [$coAuthor->secondAuthor->id]) !!}">
                    {!! $coAuthor->secondAuthor->given_name.' '.$coAuthor->secondAuthor->surname !!}
                </a>
            </td>

            <td>
                {!! Form::open(['route' => ['coAuthors.destroy', $coAuthor->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('coAuthors.show', [$coAuthor->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('coAuthors.edit', [$coAuthor->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
