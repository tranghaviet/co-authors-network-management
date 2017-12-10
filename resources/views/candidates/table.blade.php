<table class="table table-responsive" id="candidates-table">
    <thead>
    <tr>
        <th>First Author</th>
        <th>Second Author</th>
        <th>Score 1</th>
        <th>Score 2</th>
        <th>Score 3</th>
        @if (\App\Helpers\Utility::displayForAdmin())
            <th colspan="3">Action</th>
        @endif
    </tr>
    </thead>
    <tbody>
    @foreach($candidates as $candidate)
        <tr>
            <td>

                <a href="{!! route($routeType . 'authors.show', [$candidate['co_author']['first_author']['id']]) !!}">
                    {!! $candidate['co_author']['first_author']['given_name'].' '.$candidate['co_author']['first_author']['surname'] !!}
                </a>
            </td>
            <td>
                <a href="{!! route($routeType . 'authors.show', [$candidate['co_author']['second_author']['id']]) !!}">
                    {!! $candidate['co_author']['second_author']['given_name'].' '.$candidate['co_author']['second_author']['surname'] !!}
                </a>
            </td>
            <td>{!! $candidate['score_1'] !!}</td>
            <td>{!! $candidate['score_2'] !!}</td>
            <td>{!! $candidate['score_3'] !!}</td>
            @if (\App\Helpers\Utility::displayForAdmin())
                <td>
                    {!! Form::open(['route' => ['candidates.destroy', $candidate['co_author_id']], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{!! route('candidates.show', [$candidate['co_author_id']]) !!}"
                           class='btn btn-default btn-xs'><i
                                    class="fa fa-eye"></i></a>
                        <a href="{!! route('candidates.edit', [$candidate['co_author_id']]) !!}"
                           class='btn btn-default btn-xs'><i
                                    class="fa fa-edit"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            @endif
        </tr>
    @endforeach
    </tbody>
</table>
