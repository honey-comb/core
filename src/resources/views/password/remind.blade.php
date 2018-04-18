@extends('HCCore::auth.master')

@section('content')

    <div class="login-page" style="position: fixed; top:0; left: 0; bottom: 0; right: 0;">

        <div class="login-box">
            <div class="login-logo">
                <b>{{ config('app.name') }}</b>
            </div>

            <!-- /.login-logo -->
            <div class="login-box-body">
                <p class="login-box-msg">{{ trans('HCCore::user.passwords.remind') }}</p>

                <div id="password-remind-form"></div>

                <a href="{{ route('auth.login')}}">{{ trans('HCCore::user.login.title') }}</a><br>
            </div>
        </div>

    </div>
@endsection

@section('scripts')

    <script>
        HC.react.enableFaIcons();
        HC.react.hcForm({
            url: '{{route('frontend.api.form-manager', 'password-remind-new')}}',
            divId: 'password-remind-form'
        })
    </script>
@endsection
