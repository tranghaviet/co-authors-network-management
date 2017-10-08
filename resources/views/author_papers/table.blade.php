<table class="table table-responsive" id="authorPapers-table">
    <thead>
        <tr>
            <th>Author Id</th>
        <th>Paper Id</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($authorPapers as $authorPaper)
        <tr>
            <td>{!! $authorPaper->author_id !!}</td>
            <td>{!! $authorPaper->paper_id !!}</td>
            <td>
                {!! Form::open(['route' => ['authorPapers.destroy', $authorPaper->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
{{--                    <a href="{!! route('authorPapers.show', [$authorPaper->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>--}}
{{--                    <a href="{!! route('authorPapers.edit', [$authorPaper->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>--}}
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
