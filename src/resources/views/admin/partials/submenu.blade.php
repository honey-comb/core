@if(! is_null($menuItems))

    @foreach($menuItems as $item)

        {{-- this submenu creation is depends on honeycomb config.json packages files parameter aclPermission --}}
        {{-- if the given user role has access to this permission than user can see that menu --}}

        <li
                @if(isset($item['children']) && is_array($item['children']))
                @if (checkActiveMenuItems($item, request()->route()->getName()))
                class="treeview active menu-open"
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

                    @if(isset($item['parentConfig']))

                        @if(isset($item['parentConfig']['iconPath']) && ! empty($item['parentConfig']['iconPath']))
                            <img src="{{ $item['parentConfig']['iconPath'] }}"
                                 class="{{ $item['parentConfig']['iconParams']['class'] or '' }}"
                                 style="{{ $item['iconParams']['style'] or '' }}"
                                 width="15"
                                 alt=""/>
                        @else
                            {!!  fontAwesomeIcon($item['parentConfig']['icon']) !!}
                        @endif

                        <span>{{ trans($item['parentConfig']['translation']) }}</span>
                    @else

                        @if(isset($item['iconPath']) && ! empty($item['iconPath']))
                            <img src="{{ $item['iconPath'] }}"
                                 class="{{ $item['iconParams']['class'] or '' }}"
                                 style="{{ $item['iconParams']['style'] or '' }}"
                                 width="15"
                                 alt=""/>
                        @else
                            {!!  fontAwesomeIcon($item['icon']) !!}
                        @endif

                        <span>{{ trans($item['translation']) }}</span>
                    @endif

                    <span class="pull-right-container">
                        {!!  fontAwesomeIcon('angle-left', "", "fa-angle-left pull-right") !!}
                    </span>
                </a>

                {{-- if menu item is available and children are available than display second level menu --}}
                <ul class="treeview-menu">
                    @if($item['route'] != 'admin.index')

                        <li @if($item['route'] == request()->route()->getName()) class="active" @endif>

                            <a href="{{ route($item['route']) }}" target="{{ isset($item['target']) ? $item['target'] : '_self' }}">
                                @if(isset($item['skipList']))

                                    @if(isset($item['iconPath']) && ! empty($item['iconPath']))
                                        <img src="{{ $item['iconPath'] }}"
                                             width="15"
                                             class="{{ $item['iconParams']['class'] or '' }}"
                                             style="{{ $item['iconParams']['style'] or '' }}"
                                             alt=""/>
                                    @else
                                        {!!  fontAwesomeIcon($item['icon']) !!}

                                    @endif

                                    {{ trans($item['translation']) }}
                                @else

                                    {!!  fontAwesomeIcon('list-ul') !!}
                                    {{ trans('HCCore::core.list') }}
                                @endif
                            </a>
                        </li>
                    @endif

                    @include('HCCore::admin.partials.submenu', ['menuItems' => $item['children']])
                </ul>
            @else

                {{--show without dropdown--}}
                <a href="{{ route($item['route']) }}" target="{{ isset($item['target']) ? $item['target'] : '_self' }}">
                    @if(isset($item['iconPath']) && ! empty($item['iconPath']))
                        <img src="{{ $item['iconPath'] }}"
                             width="15"
                             class="{{ $item['iconParams']['class'] or '' }}"
                             style="{{ $item['iconParams']['style'] or '' }}"
                             alt=""/>
                    @else
                        {!!  fontAwesomeIcon($item['icon']) !!}
                    @endif

                    {{ trans($item['translation']) }}
                </a>
            @endif
        </li>

    @endforeach

@endif
