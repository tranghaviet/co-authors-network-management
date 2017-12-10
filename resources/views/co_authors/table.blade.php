<table class="table table-responsive" id="coAuthors-table">
    <thead>
        <tr>
            <th>Id</th>
            <th>First Author</th>
            <th>University</th>
            <th>Second Author</th>
            <th>University</th>
            <th>Mutual authors</th>
            <th>Joint papers</th>
            @if (\App\Helpers\Utility::displayForAdmin())
                <th colspan="3">Action</th>
            @endif
        </tr>
    </thead>
    <tbody>
    @foreach($coAuthors as $coAuthor)
        <!-- <tr>{!!count($coAuthor)!!}</tr> -->
        <tr>
            <td>{!! $coAuthor['id'] !!}</td>
            <td>
                <a href="{!! route('authors.show', [$coAuthor['first_author_id']]) !!}">
                    {!! $coAuthor['first_author']['given_name'].' '.$coAuthor['first_author']['surname'] !!}
                </a>
            </td>
            <td>
                {!! $coAuthor['first_author']['university']['name'] !!}
            </td>
            <td>
                <a href="{!! route('authors.show', [$coAuthor['second_author_id']]) !!}">
                    {!! $coAuthor['second_author']['given_name'].' '.$coAuthor['second_author']['surname'] !!}
                </a>
            </td>
            <td>
                {!! $coAuthor['second_author']['university']['name'] !!}
            </td>
            <td>
                {{ $coAuthor['no_of_mutual_authors'] }}
            </td>
            <td>
                {{ $coAuthor['no_of_joint_papers'] }}
            </td>
            @if (\App\Helpers\Utility::displayForAdmin())
            <td>
                {!! Form::open(['route' => ['coAuthors.destroy', $coAuthor['id']], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('coAuthors.show', [$coAuthor['id']]) !!}" class='btn btn-default btn-xs'><i class="fa fa-eye"></i></a>
                    <a href="{!! route('coAuthors.edit', [$coAuthor['id']]) !!}" class='btn btn-default btn-xs'><i class="fa fa-edit"></i></a>
                    {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
            @endif
        </tr> 
    @endforeach
    </tbody>
</table>
