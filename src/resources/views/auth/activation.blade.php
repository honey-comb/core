@extends('HCCore::auth.master')

@section('content')

    <div class="login-page" style="position: fixed; top:0; left: 0; bottom: 0; right: 0;">

        <div class="login-box">
            <div class="login-logo">
                <b>{{ config('app.name') }}</b>
            </div>

            <h3 class="text-center">{{ trans('HCCore::user.activation.title') }}</h3>

            @if(! is_null($message))

                <p class="narrow text-center">
                    {{ $message }}
                </p>

                <div class="form-group text-center">
                    <a href="/">
                        <button class="btn btn-info" type="submit">
                            {{ trans('HCCore::user.activation.back_to_main') }}
                        </button>
                    </a>
                </div>
            @else

                <p class="narrow text-center">
                    {{ trans('HCCore::user.activation.info') }}
                </p>

                <form id="form" method="POST" action="{{ route('auth.activation.post') }}">
                    @if (count($errors->all()) > 0)
                        <div class="is-fm-error-holder is-fm-error-holder-animation">
                            @foreach ($errors->all() as $error)
                                <div class="errorMessage">{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-group">
                        <p class="text-center">
                            <button class="btn btn-success" type="submit">
                                {{ trans('HCCore::user.activation.activate') }}
                            </button>
                        </p>
                    </div>
                </form>
            @endif

        </div>

    </div>

    {{--<div class="container">--}}
    {{--<div class="tab-content">--}}

    {{----}}
    {{--</div>--}}
    {{--</div>--}}
@stop
