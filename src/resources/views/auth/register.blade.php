@extends('HCCore::auth.master')

@section('content')

    <div class="login-page" style="position: fixed; top:0; left: 0; bottom: 0; right: 0;">

        <div class="login-box">
            <div class="login-logo">
                <b>HoneyComb</b>
            </div>

            <!-- /.login-logo -->
            <div class="login-box-body">
                <p class="login-box-msg">{{ trans('HCCore::user.register.title') }}</p>

                <div id="register-form"></div>

                <div class="social-auth-links text-center">

                </div>

                <hr/>
                <div class="text-center">
                    <a href="{{ route('auth.login') }}">{{ trans('HCCore::user.login.title') }}</a>
                </div>

            </div>
        </div>

    </div>
@stop

@section('scripts')
    <script>
        $(document).ready(function () {
            HCService.FormManager.initialize();
            var form = HCService.FormManager.createForm({
                'structureURL': '{{  route('frontend.api.form-manager', 'users-register-new')}}',
                'divID': '#register-form'
            });
        });
    </script>
@endsection
