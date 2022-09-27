@php $userRolePermissions = auth()->user()->getAllPermissionArray() @endphp

@php($is_in_list_unstyled = 0)
@if(count(auth()->user()->role->permissions) <= 10)
    @php($is_in_list_unstyled = 1)
@endif

<ul class="list-settings components">
    @foreach($menuItems as $groupTitle => $groupMenuItems)
        @if(is_array($groupMenuItems))
            @if(isset($userRolePermissions[$groupTitle]))
                <li>


                    @if($is_in_list_unstyled == 1)

                        <span class="list-settings-item" style="padding: 0px 5px 5px 10px;font-weight: bold;font-size: 14px;color: #000000">
                            {{ trans('menu.' . $menuItems[$groupTitle]['form_title']) }}
                        </span>

                    @else

                        <a href="#submenu-{{$groupTitle}}" class="list-settings-item" @if(is_array($menuItems[$groupTitle]['links'])) data-toggle="collapse" aria-expanded="false" @endif>
                            <span class="list-settings-item-ico {{$menuItems[$groupTitle]['ico']}}"></span>

                            {{ trans('menu.' . $menuItems[$groupTitle]['form_title']) }}
                        </a>

                    @endif


                    <ul class="collapse @if($is_in_list_unstyled == 1)in @endif list-unstyled" id="submenu-{{$groupTitle}}">
                        @foreach($menuItems[$groupTitle]['links'] as $menuItem => $menuItemTitle)

                            @if(isset($userRolePermissions[$groupTitle][$menuItemTitle['name']]))
                                <li>
                                    <a href="{{ url($groupTitle . "/" . $menuItemTitle['link']) }}/" class="list-settings-item sub-item">
                                        <span class="list-settings-item-ico {{$menuItemTitle['ico']}}"></span>
                                        {{ trans('menu.'.$menuItemTitle['name']) }}
                                    </a>
                                </li>
                            @endif

                        @endforeach
                    </ul>
                </li>
            @endif
        @endif
    @endforeach
</ul>


