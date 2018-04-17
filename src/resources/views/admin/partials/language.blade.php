@if(sizeof($adminLanguages) > 1)

    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
           aria-haspopup="true" aria-expanded="false">
            @if(in_array(app()->getLocale(), array_pluck($adminLanguages, 'iso_639_1')))
                @foreach($adminLanguages as $language)
                    @if($language->iso_639_1 == app()->getLocale())
                        {{ $language->native_name }}
                    @endif
                @endforeach
            @else
                {{ trans('HCCore::language.select_language') }}
            @endif
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            @foreach($adminLanguages as $language)
                @if ($language->iso_639_1 !==app()->getLocale() )
                    <a class="dropdown-item" href="{{ route('language.change', ['back-end', $language->iso_639_1]) }}">
                        {{ $language->native_name }}
                    </a>
                @endif
            @endforeach
        </div>
    </li>

@endif
