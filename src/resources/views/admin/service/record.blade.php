@extends('HCCore::admin.layout.master')

@section('content')
    <div id="admin-form"></div>
@endsection

@section('scripts')

    {{-- admin list --}}
    <script src="{{mix('js/hc-full.js')}}"></script>

    <script>

        let config = {!! json_encode($config) !!};
        config.divId = 'admin-form';

        HC.react.enableFaIcons();
        HC.react.enableToastContainer();
        HC.react.hcForm(config);

    </script>

@endsection
