<li class="{{ Request::is('admin/users*') ? 'active' : '' }}">
    <a href="{!! route('users.index') !!}"><i class="fa fa-users"></i><span>Users</span></a>
</li>

<li class="{{ Request::is('admin/authors*') ? 'active' : '' }}">
    <a href="{!! route('authors.index') !!}"><i class="fa fa-flask"></i><span>Authors</span></a>
</li>

<li class="{{ Request::is('admin/papers*') ? 'active' : '' }}">
    <a href="{!! route('papers.index') !!}"><i class="fa fa-newspaper-o"></i><span>Papers</span></a>
</li>

<li class="{{ Request::is('admin/authorPapers*') ? 'active' : '' }}">
    <a href="{!! route('authorPapers.index') !!}"><i class="fa fa-pencil"></i><span>Author Papers</span></a>
</li>

<li class="{{ Request::is('admin/coAuthors*') ? 'active' : '' }}">
    <a href="{!! route('coAuthors.index') !!}"><i class="fa fa-handshake-o"></i><span>Co-authors</span></a>
</li>

<li class="{{ Request::is('admin/candidates*') ? 'active' : '' }}">
    <a href="{!! route('candidates.index') !!}"><i class="fa fa-user-plus"></i><span>Candidates</span></a>
</li>

<li class="{{ Request::is('admin/universities*') ? 'active' : '' }}">
    <a href="{!! route('universities.index') !!}"><i class="fa fa-university"></i><span>Universities</span></a>
</li>

<li class="{{ Request::is('admin/cities*') ? 'active' : '' }}">
    <a href="{!! route('cities.index') !!}"><i class="fa fa-building"></i><span>Cities</span></a>
</li>

<li class="{{ Request::is('admin/countries*') ? 'active' : '' }}">
    <a href="{!! route('countries.index') !!}"><i class="fa fa-flag-checkered"></i><span>Countries</span></a>
</li>

<li class="{{ Request::is('admin/sync*') ? 'active' : '' }}">
    <a href="{!! route('sync.index') !!}"><i class="fa fa-refresh"></i><span>Sync</span></a>
</li>

<li class="{{ Request::is('admin/uploadAuthors*') ? 'active' : '' }}">
    <a href="{!! route('view_upload_authors') !!}"><i class="fa fa-upload"></i><span>Import Authors</span></a>
</li>

<li class="{{ Request::is('admin/uploadPapers*') ? 'active' : '' }}">
    <a href="{!! route('view_upload_papers') !!}"><i class="fa fa-upload"></i><span>Import Papers</span></a>
</li>

<li class="{{ Request::is('admin/uploadAuthorPaper*') ? 'active' : '' }}">
    <a href="{!! route('view_upload_authors_papers') !!}"><i class="fa fa-upload"></i><span>Import Author-Paper</span></a>
</li>

