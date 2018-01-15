@extends('HCCore::admin.layout.master')

@section('content')
    <div class="row">
        <div class="info-box">
                <span class="info-box-icon bg-yellow">
                    {!! fontAwesomeIcon('users') !!}
                </span>

            <div class="info-box-content">
                <span class="info-box-text">{{ trans('HCCore::core.users') }}</span>
                <span class="info-box-number">{{ \HoneyComb\Core\Models\HCUser::count() }}</span>
            </div>
        </div>
        <div class="info-box">
                <span class="info-box-icon bg-green">
                    {!! fontAwesomeIcon('hdd') !!}
                </span>

            <div class="info-box-content">
                <span class="info-box-text">{{ trans('HCCore::core.size') }}</span>
                <span class="info-box-number">{{ getProjectFileSize() }}</span>
            </div>
        </div>
        <div class="info-box">
                <span class="info-box-icon bg-light-blue">
                    {!! fontAwesomeIcon('database') !!}
                </span>

            <div class="info-box-content">
                <span class="info-box-text">{{ trans('HCCore::core.size') }}</span>
                <span class="info-box-number">{{ getProjectDbSize() }}</span>
            </div>
        </div>
    </div>
@endsection

@section ('scripts')
    {{-- admin list --}}
    <script src="{{mix('js/hc-full.js')}}"></script>

    <script>
        HC.react.enableFaIcons();
    </script>
@endsection
