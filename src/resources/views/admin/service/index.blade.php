@extends('HCCore::admin.layout.master')

{{--@if ( isset( $config['title'] ) &&  ! empty($config['title']))
    @section('content-header',  $config['title'] )
@endif--}}

@section('content')

    <div id="admin-list" data-hc="parameters"></div>
    <div id="admin-form"></div>
    <div id="toastrify"></div>

@endsection

@section('scripts')

    {{-- admin list --}}
    <script src="{{mix('js/hc-full.js')}}"></script>

    <script>
        HC.react.enableFaIcons();
        HC.react.adminList({!! json_encode($config) !!});
    </script>

@endsection
