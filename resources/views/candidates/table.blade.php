<table class="table table-responsive" id="candidates-table">
    <thead>
        <tr>
            <th>Co Author Id</th>
            <th>No. of Mutual Authors</th>
            <th>No. of Joint Papers</th>
            <th>No. of Joint Subjects</th>
            <th>No. of Joint Keywords</th>
            <th>Score 1</th>
            <th>Score 2</th>
            <th>Score 3</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($candidates as $candidate)
        <tr>
            <td>
                <a href="{!! route('authors.show', [$candidate->coAuthor->firstAuthor->id]) !!}">
                    {!! $candidate->coAuthor->firstAuthor->given_name.' '.$candidate->coAuthor->firstAuthor->surname !!}
                </a>
            </td>
            <td>
                <a href="{!! route('authors.show', [$candidate->coAuthor->secondAuthor->id]) !!}">
                    {!! $candidate->coAuthor->secondAuthor->given_name.' '.$candidate->coAuthor->secondAuthor->surname !!}
                </a>
            </td>
            <td>{!! $candidate->no_of_joint_papers !!}</td>
            <td>{!! $candidate->no_of_joint_subjects !!}</td>
            <td>{!! $candidate->no_of_joint_keywords !!}</td>
            <td>{!! $candidate->score_1 !!}</td>
            <td>{!! $candidate->score_2 !!}</td>
            <td>{!! $candidate->score_3 !!}</td>
            <td>
                {!! Form::open(['route' => ['candidates.destroy', $candidate->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('candidates.show', [$candidate->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('candidates.edit', [$candidate->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
