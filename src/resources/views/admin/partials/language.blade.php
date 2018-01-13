@inject('languageRepository', 'HoneyComb\Core\Repositories\HCLanguageRepository')

<li class="dropdown languages tasks-menu">

    @php
        $languages = \HoneyComb\Core\Models\HCLanguage::where('back_end', '1')->get();
    @endphp

    @if(in_array(app()->getLocale(), array_pluck($languages, 'iso_639_1')))
        @foreach($languages as $language)
            @if($language->iso_639_1 == app()->getLocale())
                <a title="{{$language->native_name}}" class="dropdown-toggle" data-toggle="dropdown"
                   href="#">
                    {{ $language->native_name }} <i class="fa fa-angle-down"></i>
                </a>
            @endif
        @endforeach
    @else
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            {{ trans('HCCore::language.select_language') }}
            <i class="fa fa-angle-down"></i>
        </a>
    @endif

    <ul class="dropdown-menu">
        <li class="header">{{ trans('HCCore::language.select_language') }}</li>
        <li>
            <ul class="menu">
                @foreach($languages as $language)
                    <li class="{{ $language->iso_639_1 == app()->getLocale() ? 'active' : '' }}">
                        <a href="{{ route('language.change', ['back-end', $language->iso_639_1]) }}">
                            {{ $language->native_name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </li>
    </ul>
</li>
