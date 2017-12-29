@if(! is_null($menuItems))

    @foreach($menuItems as $item)

        {{-- this submenu creation is depends on honeycomb config.json packages files parameter aclPermission --}}
        {{-- if the given user role has access to this permission than user can see that menu --}}


        <li
                @if(isset($item['children']) && is_array($item['children']))
                        @if (checkActiveMenuItems($item, request()->route()->getName()))
                            class="treeview active"
                        @else
                            class="treeview"
                        @endif
                @elseif(request()->route()->getName() == $item['route'] )
                class="active"
                @endif
        >

        @if(isset($item['children']) && is_array($item['children']))

                {{--if it has allowed children to show children than add dropdown --}}
                <a href="#">
                    @if(isset($item['iconPath']) && ! empty($item['iconPath']))
                        <img src="{{ $item['iconPath'] }}" class="{{ $item['iconParams']['class'] or '' }}"
                             style="{{ $item['iconParams']['style'] or '' }}" width="15" alt=""/>
                    @else
                        <i class="fa {{ $item['icon'] }} fa-fw {{ $item['iconParams']['class'] or '' }}"
                           style="{{ $item['iconParams']['style'] or '' }}"></i>
                    @endif

                    <span>{{ trans($item['translation']) }}</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>

                {{-- if menu item is available and children are available than display second level menu --}}
                <ul class="treeview-menu">
                    @if($item['route'] != 'admin.index')
                        <li @if($item['route'] == request()->route()->getName()) class="active" @endif>
                            <a href="{{ route($item['route']) }}">
                                @if(isset($item['listTranslation']))
                                    @if(isset($item['listIconPath']) && ! empty($item['listIconPath']))
                                        <img src="{{ $item['listIconPath'] }}" width="15" alt=""/>
                                    @else
                                        @if (isset($item['listIcon']) && ! empty($item['listIcon']))
                                            <i class="fa {{ $item['listIcon'] }} fa-fw {{ $item['iconParams']['class'] or '' }}"
                                               style="{{ $item['iconParams']['style'] or '' }}"></i>
                                        @else
                                            <i class="fa fa-list-ul fa-fw {{ $item['iconParams']['class'] or '' }}"
                                               style="{{ $item['iconParams']['style'] or '' }}"></i>
                                        @endif
                                    @endif
                                    {{ trans($item['listTranslation']) }}
                                @else
                                    <i class="fa fa-list-ul fa-fw"></i>
                                    {{ trans('HCCore::core.list') }}
                                @endif
                            </a>
                        </li>
                    @endif

                    @include('HCCore::admin.partials.submenu', ['menuItems' => $item['children']])
                </ul>
            @else

                {{--show without dropdown--}}
                <a href="{{ route($item['route']) }}">
                    @if(isset($item['iconPath']) && ! empty($item['iconPath']))
                        <img src="{{ $item['iconPath'] }}" width="15" class="{{ $item['iconParams']['class'] or '' }}" style="{{ $item['iconParams']['style'] or '' }}" alt=""/>
                    @else
                        <i class="fa {{ $item['icon'] }} fa-fw {{ $item['iconParams']['class'] or '' }}" style="{{ $item['iconParams']['style'] or '' }}"></i>
                    @endif

                    {{ trans($item['translation']) }}
                </a>
            @endif
        </li>

    @endforeach

@endif
