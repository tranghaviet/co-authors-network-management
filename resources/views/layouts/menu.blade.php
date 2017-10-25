<li class="{{ Request::is('users*') ? 'active' : '' }}">
    <a href="{!! route('users.index') !!}"><i class="fa fa-users"></i><span>Users</span></a>
</li>

<li class="{{ Request::is('authors*') ? 'active' : '' }}">
    <a href="{!! route('authors.index') !!}"><i class="fa fa-flask"></i><span>Authors</span></a>
</li>

<li class="{{ Request::is('papers*') ? 'active' : '' }}">
    <a href="{!! route('papers.index') !!}"><i class="fa fa-newspaper-o"></i><span>Papers</span></a>
</li>

<li class="{{ Request::is('authorPapers*') ? 'active' : '' }}">
    <a href="{!! route('authorPapers.index') !!}"><i class="fa fa-pencil"></i><span>Author Papers</span></a>
</li>

<li class="{{ Request::is('coAuthors*') ? 'active' : '' }}">
    <a href="{!! route('coAuthors.index') !!}"><i class="fa fa-handshake-o"></i><span>Co-authors</span></a>
</li>

<li class="{{ Request::is('candidates*') ? 'active' : '' }}">
    <a href="{!! route('candidates.index') !!}"><i class="fa fa-user-plus"></i><span>Candidates</span></a>
</li>

<li class="{{ Request::is('universities*') ? 'active' : '' }}">
    <a href="{!! route('universities.index') !!}"><i class="fa fa-university"></i><span>Universities</span></a>
</li>

<li class="{{ Request::is('cities*') ? 'active' : '' }}">
    <a href="{!! route('cities.index') !!}"><i class="fa fa-building"></i><span>Cities</span></a>
</li>

<li class="{{ Request::is('countries*') ? 'active' : '' }}">
    <a href="{!! route('countries.index') !!}"><i class="fa fa-flag-checkered"></i><span>Countries</span></a>
</li>

