@extends('HCCore::admin.layout.master')

@if ( isset( $config['title'] ) &&  ! empty($config['title']))
    @section('content-header',  $config['title'] )
@endif

@section('content')

    <div id="here-comes-form"></div>

@endsection

@section('scripts')
    <script>
        $().ready(function () {
            var config = {!! json_encode($config) !!};
            config.divID = '#here-comes-form';

            var form = ISService.FormManager.createForm(config);
        });
    </script>
@endsection
