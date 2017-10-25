<table class="table table-responsive" id="authorPapers-table">
    <thead>
        <tr>
            <th>Author</th>
            <th>Paper</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($authorPapers as $authorPaper)
        <tr>
            <td>
                <a href="{!! route('authors.show', [$authorPaper->author['id']]) !!}">
                    {{ $authorPaper->author['given_name'].' '.$authorPaper->author['surname'] }}
                </a>
            </td>
            <td>
                <a href="{!! route('papers.show', [$authorPaper->paper['id']]) !!}">
                    {{--{{ dump($authorPaper) }}--}}
                    {{ $authorPaper->paper['title'] }}
                </a>
            <td>
                {!! Form::open(['route' => ['authorPapers.destroy', $authorPaper['id']], 'method' => 'delete']) !!}
                <div class='btn-group'>
{{--                    <a href="{!! route('authorPapers.show', [$authorPaper->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-eye"></i></a>--}}
{{--                    <a href="{!! route('authorPapers.edit', [$authorPaper->id]) !!}" class='btn btn-default btn-xs'><i class="fa fa-edit"></i></a>--}}
                    {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
