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

@yield('scripts')

<script>
    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
</body>
</html>
