<!DOCTYPE html>
<html>
<head>
    <title>Main</title>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    @include('HCCore::admin.assets.css')

</head>
<body>

@yield('content')

@include('HCCore::admin.assets.js')

@include('HCCore::admin.partials.toastrify')

</body>
{{-- admin list --}}
<script src="{{mix('js/hc-full.js')}}"></script>
<script>
    HC.react.enableToastContainer();
</script>

@yield('scripts')


</html>
