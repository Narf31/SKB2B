@if(auth()->user()->hasPermission('directories', 'organizations_edit'))
    @include('organizations.organizations.partials.form_edit', ['organization' => $organization, 'send_urls' => $send_urls])
@else

    @if(auth()->user()->id == $organization->parent_user_id)
        @include('organizations.organizations.partials.form_min_edit', ['organization' => $organization, 'send_urls' => $send_urls])
    @else
        @include('organizations.organizations.partials.form_view', ['organization' => $organization, 'send_urls' => $send_urls])
    @endif

@endif