

<ul class="nav navbar-nav">


    @foreach($menuItems as $groupTitle => $groupMenuItems)

        @if(is_array($groupMenuItems))

            @if(auth()->user()->hasGroupPermission($groupTitle))


                <li class="dropdown ">

                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ trans('menu.' . $groupTitle) }}</a>

                    <ul class="dropdown-menu">

                        @foreach($groupMenuItems as $menuItemTitle)

                            @if(auth()->user()->hasPermission($groupTitle, $menuItemTitle))
                                <li>
                                    <a href="{{ url($groupTitle . "/" . $menuItemTitle) }}">{{ trans('menu.'.$menuItemTitle) }}</a>
                                </li>
                            @endif

                        @endforeach

                    </ul>

                </li>

            @endif

        @endif

    @endforeach

</ul>