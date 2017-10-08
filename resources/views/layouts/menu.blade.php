<li class="{{ Request::is('users*') ? 'active' : '' }}">
    <a href="{!! route('users.index') !!}"><i class="fa fa-edit"></i><span>Users</span></a>
</li>

<li class="{{ Request::is('authors*') ? 'active' : '' }}">
    <a href="{!! route('authors.index') !!}"><i class="fa fa-edit"></i><span>Authors</span></a>
</li>

<li class="{{ Request::is('papers*') ? 'active' : '' }}">
    <a href="{!! route('papers.index') !!}"><i class="fa fa-edit"></i><span>Papers</span></a>
</li>

<li class="{{ Request::is('authorPapers*') ? 'active' : '' }}">
    <a href="{!! route('authorPapers.index') !!}"><i class="fa fa-edit"></i><span>Author Papers</span></a>
</li>

<li class="{{ Request::is('coAuthors*') ? 'active' : '' }}">
    <a href="{!! route('coAuthors.index') !!}"><i class="fa fa-edit"></i><span>Co Authors</span></a>
</li>

<li class="{{ Request::is('candidates*') ? 'active' : '' }}">
    <a href="{!! route('candidates.index') !!}"><i class="fa fa-edit"></i><span>Candidates</span></a>
</li>

