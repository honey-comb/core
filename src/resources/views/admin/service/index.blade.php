@extends('HCCore::admin.layout.master')

{{--@if ( isset( $config['title'] ) &&  ! empty($config['title']))
    @section('content-header',  $config['title'] )
@endif--}}

@section('content')

    <div id="admin-list" data-hc="parameters"></div>
    <div id="admin-form"></div>

@endsection

@section('scripts')

    {{-- admin list --}}
    <script src="{{mix('js/hc-full.js')}}"></script>

    <script>
        HC.react.enableFaIcons();
        HC.react.adminList({!! json_encode($config) !!});
    </script>

    {{--<script>
        $(document).ready(function () {
            new HCService.List.SimpleList({
                div: '#here-comes-list',

                @include('HCCore::admin.partials.list-settings')
            });
        });
    </script>

    @if(config('hc.google_map_api_key'))
        <script src="https://maps.googleapis.com/maps/api/js?key={{ config('hc.google_map_api_key') }}&libraries=places"></script>
    @endif--}}

@endsection
