<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }} {{ trans('HCCore::core.administration') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    @include('HCCore::admin.assets.css')

    @yield('css')
</head>
<body class="{{config('hc.admin_skin')}}">
<div class="wrapper">

    @if(app()->environment() == "local")
        <div id="dev-env">
            {!! trans('HCCore::core.dev_env') !!}
        </div>
    @endif

    @include('HCCore::admin.partials.header')

    @include('HCCore::admin.partials.sidebar')

    <div class="content-wrapper">
        <section class="content">

            @yield('content')

        </section>
    </div>

    @include('HCCore::admin.partials.footer')

    @include('HCCore::admin.partials.right-sidebar')

</div>

{{-- js include --}}
@include('HCCore::admin.assets.js')

<script>
    {{-- TODO read from cache --}}
        HCService.FRONTENDLanguage = HCService.CONTENTLanguage = '{{ app()->getLocale() }}';
</script>

<script>
    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

@yield('scripts')

@include('HCCore::admin.partials.sidebar-filter-js')
</body>
</html>
